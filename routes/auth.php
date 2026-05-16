<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyOtpController;
use Illuminate\Support\Facades\Route;

// ─────────────────────────────────────────────────────────────────────────────
// REMOVED (Breeze default magic-link verification — replaced by code system):
//   EmailVerificationNotificationController
//   EmailVerificationPromptController
//   VerifyEmailController
//   GET  verify-email                 (verification.notice)
//   GET  verify-email/{id}/{hash}     (verification.verify)
//   POST email/verification-notification (verification.send)
//
// ADDED (custom 8-char code verification):
//   EmailVerificationController
//   GET  verify-email               (verification.code.show)
//   POST verify-email/verify        (verification.code.verify)
//   POST verify-email/resend        (verification.code.resend)
//
// KEPT UNTOUCHED:
//   OTP password reset (VerifyOtpController) — no conflict
// ─────────────────────────────────────────────────────────────────────────────

Route::middleware('guest')->group(function () {

    Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('register');

    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    // ── OTP-based Password Reset (your existing system — untouched) ───────────

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    Route::get('verify-otp', [VerifyOtpController::class, 'create'])
        ->name('password.verify-otp');

    Route::post('verify-otp', [VerifyOtpController::class, 'store'])
        ->name('password.verify-otp.store');

    Route::post('verify-otp/resend', [VerifyOtpController::class, 'resend'])
        ->name('password.verify-otp.resend');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset.otp');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');

});

Route::middleware('auth')->group(function () {

    // ── Custom code-based email verification ──────────────────────────────────
    // Only 'auth' here — NOT 'email.verified' (would cause infinite redirect loop)

    Route::get('verify-email', [EmailVerificationController::class, 'show'])
        ->name('verification.code.show');

    Route::post('verify-email/verify', [EmailVerificationController::class, 'verify'])
        ->name('verification.code.verify');

    Route::post('verify-email/resend', [EmailVerificationController::class, 'resend'])
        ->name('verification.code.resend');

    // ── Confirm password ──────────────────────────────────────────────────────

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('password', [PasswordController::class, 'update'])
        ->name('password.update');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');

});
