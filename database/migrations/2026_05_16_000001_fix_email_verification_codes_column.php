<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * ROOT CAUSE (confirmed from laravel.log line 300):
 *
 *   SQLSTATE[22001]: String data, right truncated:
 *   1406 Data too long for column 'code' at row 1
 *
 * The `email_verification_codes` table was originally created with a
 * `code` column that is too short (VARCHAR(6) — copied from the
 * forgot-password OTP migration which uses 6-digit codes).
 *
 * EmailVerificationCode::generateCode() produces an 8-character string.
 * MySQL rejects the INSERT, throwing a QueryException before
 * Mail::send() is ever called. The email never sent because the code
 * was never saved — this was not a mail configuration problem.
 *
 * This migration:
 *   1. Widens `code` to VARCHAR(8) — exactly the length generateCode() produces.
 *   2. Adds `used_at` (nullable timestamp) so codes can be invalidated after
 *      use, preventing replay attacks (EmailVerificationCode::markAsUsed()).
 *
 * Run with:
 *   php artisan migrate
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('email_verification_codes', function (Blueprint $table) {

            // ── Fix 1: widen the code column to exactly 8 characters ──
            // change() requires doctrine/dbal on Laravel <11, but on
            // Laravel 12 it uses the native Doctrine-free column modifier.
            $table->string('code', 8)->change();

            // ── Fix 2: add used_at if it doesn't already exist ────────
            if (! Schema::hasColumn('email_verification_codes', 'used_at')) {
                $table->timestamp('used_at')->nullable()->after('expires_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('email_verification_codes', function (Blueprint $table) {
            // Revert code width — truncation may occur if existing rows
            // have 8-char codes, so this is only safe on a dev database.
            $table->string('code', 6)->change();

            if (Schema::hasColumn('email_verification_codes', 'used_at')) {
                $table->dropColumn('used_at');
            }
        });
    }
};
