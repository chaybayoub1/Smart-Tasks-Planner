<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\PasswordResetOtp;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class NewPasswordController extends Controller
{
    /**
     * Display the password reset form.
     * Guard: user must have passed OTP verification in this session.
     */
    public function create(Request $request): View|RedirectResponse
    {
        // Verify the URL token matches the session token set after OTP verification
        $sessionToken = session('otp_reset_token');
        $urlToken     = $request->route('token');

        if (! session('otp_verified') || ! $sessionToken || $sessionToken !== $urlToken) {
            return redirect()->route('password.request')
                ->withErrors(['email' => 'Please complete email verification first.']);
        }

        return view('auth.reset-password', ['request' => $request]);
    }

    /**
     * Update the user's password.
     */
    public function store(Request $request): RedirectResponse
    {
        // Re-validate the session guard
        $sessionToken = session('otp_reset_token');
        $urlToken     = $request->input('token');

        if (! session('otp_verified') || ! $sessionToken || $sessionToken !== $urlToken) {
            return redirect()->route('password.request')
                ->withErrors(['email' => 'Session expired or invalid. Please start again.']);
        }

        $request->validate([
            'token'    => ['required'],
            'email'    => ['required', 'email', 'exists:users,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user) {
            return back()->withErrors(['email' => 'No user found with that email address.']);
        }

        // Update password
        $user->forceFill([
            'password'       => Hash::make($request->password),
            'remember_token' => Str::random(60),
        ])->save();

        event(new PasswordReset($user));

        // Clean up: delete OTP record and clear session keys
        PasswordResetOtp::clearFor($request->email);
        session()->forget(['otp_email', 'otp_verified', 'otp_reset_token']);

        return redirect()->route('login')
            ->with('status', 'Your password has been reset successfully. Please sign in.');
    }
}
