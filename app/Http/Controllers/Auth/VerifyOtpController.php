<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\SendOtpMail;
use App\Models\PasswordResetOtp;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class VerifyOtpController extends Controller
{
    /**
     * Show the OTP verification form.
     * Guard: if no otp_email in session, bounce back to forgot-password.
     */
    public function create(): View|RedirectResponse
    {
        if (! session('otp_email')) {
            return redirect()->route('password.request')
                ->withErrors(['email' => 'Please enter your email address first.']);
        }

        return view('auth.verify-otp');
    }

    /**
     * Validate the submitted OTP.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'otp' => ['required', 'string', 'size:6', 'regex:/^\d{6}$/'],
        ], [
            'otp.required' => 'Please enter the verification code.',
            'otp.size'     => 'The code must be exactly 6 digits.',
            'otp.regex'    => 'The code must contain only digits.',
        ]);

        $email = session('otp_email');

        if (! $email) {
            return redirect()->route('password.request')
                ->withErrors(['email' => 'Session expired. Please start again.']);
        }

        $record = PasswordResetOtp::where('email', $email)->latest()->first();

        if (! $record) {
            return back()->withErrors(['otp' => 'No verification code found. Please request a new one.']);
        }

        if ($record->isExpired()) {
            $record->delete();
            return back()->withErrors(['otp' => 'Your code has expired. Please request a new one.']);
        }

        if ($record->otp !== $request->otp) {
            return back()->withErrors(['otp' => 'Incorrect verification code. Please try again.']);
        }

        // OTP is valid — mark session as verified and store a reset token
        $resetToken = \Illuminate\Support\Str::random(64);
        session([
            'otp_verified'    => true,
            'otp_reset_token' => $resetToken,
        ]);

        return redirect()->route('password.reset.otp', ['token' => $resetToken]);
    }

    /**
     * Resend a fresh OTP to the session email.
     */
    public function resend(Request $request): RedirectResponse
    {
        $email = session('otp_email');

        if (! $email) {
            return redirect()->route('password.request')
                ->withErrors(['email' => 'Session expired. Please start again.']);
        }

        // Throttle: don't allow resend if a non-expired OTP exists and was created < 60 s ago
        $existing = PasswordResetOtp::where('email', $email)->latest()->first();
        if ($existing && ! $existing->isExpired() && $existing->created_at->diffInSeconds(now()) < 60) {
            return back()->withErrors(['otp' => 'Please wait before requesting another code.']);
        }

        PasswordResetOtp::clearFor($email);

        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        PasswordResetOtp::create([
            'email'      => $email,
            'otp'        => $otp,
            'expires_at' => now()->addMinutes(10),
        ]);

        Mail::to($email)->send(new SendOtpMail($otp, $email));

        return back()->with('status', 'A new verification code has been sent.');
    }
}
