<?php
// app/Http/Controllers/Auth/EmailVerificationController.php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\EmailVerificationCodeMail;
use App\Models\EmailVerificationCode;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\View\View;

class EmailVerificationController extends Controller
{
    // ── 1. Show the code-entry page ───────────────────────────

    public function show(Request $request): View|RedirectResponse
    {
        // Already verified → go straight to dashboard
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard'));
        }

        return view('auth.verify-email-code');
    }

    // ── 2. Handle code submission ─────────────────────────────

    public function verify(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => ['required', 'string', 'size:8'],
        ], [
            'code.required' => 'Please enter the verification code.',
            'code.size'     => 'The code must be exactly 8 characters.',
        ]);

        $user = $request->user();

        // Already verified
        if ($user->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard'));
        }

        // Throttle: max 5 attempts per minute per user
        $throttleKey = 'verify-email:' . $user->id;

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()->withErrors([
                'code' => "Too many attempts. Please wait {$seconds} seconds before trying again.",
            ]);
        }

        $record = EmailVerificationCode::findValid($user->id, trim($request->code));

        if (! $record) {
            RateLimiter::hit($throttleKey, 60);

            // Check if an expired code exists (give a better message)
            $expired = EmailVerificationCode::where('user_id', $user->id)
                ->where('code', trim($request->code))
                ->first();

            $message = $expired
                ? 'This code has expired. Please request a new one.'
                : 'Invalid verification code. Please check and try again.';

            return back()->withErrors(['code' => $message]);
        }

        // ✅ Code is valid — mark email verified and clean up
        RateLimiter::clear($throttleKey);
        $user->markEmailAsVerified();
        $record->delete();

        return redirect()->intended(route('dashboard'))
            ->with('status', 'email-verified');
    }

    // ── 3. Resend the code ────────────────────────────────────

    public function resend(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard'));
        }

        // Throttle resend: max 3 per 5 minutes per user
        $throttleKey = 'resend-verify:' . $user->id;

        if (RateLimiter::tooManyAttempts($throttleKey, 3)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()->withErrors([
                'resend' => "Please wait {$seconds} seconds before requesting another code.",
            ]);
        }

        RateLimiter::hit($throttleKey, 300); // 5-minute window

        $record = EmailVerificationCode::issueFor($user);
        Mail::to($user->email)->send(new EmailVerificationCodeMail($user, $record->code));

        return back()->with('status', 'verification-link-sent');
    }
}
