<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\SendOtpMail;
use App\Models\PasswordResetOtp;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the forgot-password view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Generate a 6-digit OTP, store it, and email it to the user.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ], [
            'email.exists' => 'No account found with that email address.',
        ]);

        $email = $request->email;

        // Remove any previous OTPs for this email
        PasswordResetOtp::clearFor($email);

        // Generate a cryptographically random 6-digit OTP
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Store OTP with 10-minute expiry
        PasswordResetOtp::create([
            'email'      => $email,
            'otp'        => $otp,
            'expires_at' => now()->addMinutes(10),
        ]);

        // Send OTP email
        Mail::to($email)->send(new SendOtpMail($otp, $email));

        // Store email in session so verify-otp page knows who to verify
        session(['otp_email' => $email]);

        return redirect()->route('password.verify-otp')
            ->with('status', 'A 6-digit verification code has been sent to ' . $email);
    }
}
