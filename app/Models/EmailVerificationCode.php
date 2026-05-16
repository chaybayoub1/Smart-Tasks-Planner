<?php
// app/Models/EmailVerificationCode.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

/**
 * ROOT-CAUSE FIX NOTES
 * ──────────────────────────────────────────────────────────────
 * 1. `use Illuminate\Support\Str` was imported but never used — removed.
 *
 * 2. findValid() did a strict string comparison against the stored code.
 *    Because generateCode() produces mixed-case characters AND the verify
 *    page's JS calls `.toUpperCase()` on paste, a user who pastes a code
 *    and then manually edits one char could submit it in a different case,
 *    causing findValid() to return null even for a technically correct code.
 *    Fixed: compare via BINARY on DB side (default) but normalise the
 *    incoming $code to the same case as stored (UPPER for the uppercase
 *    portion). Added a helper note — if you want case-insensitive matching,
 *    use whereRaw('LOWER(code) = ?', [strtolower($code)]).
 *
 * 3. issueFor() used two separate DB operations (delete then create).
 *    If the process crashed between them, no code existed for the user.
 *    Wrapped in a DB transaction for atomicity.
 *
 * 4. Added markAsUsed() so the consuming controller can invalidate a code
 *    immediately after successful verification, preventing replay attacks.
 *
 * 5. $fillable did not include 'used_at'. Added it, with cast.
 *    Run:  php artisan make:migration add_used_at_to_email_verification_codes_table
 *    Migration:  $table->timestamp('used_at')->nullable()->after('expires_at');
 * ──────────────────────────────────────────────────────────────
 */
class EmailVerificationCode extends Model
{
    protected $fillable = ['user_id', 'code', 'expires_at', 'used_at'];

    protected $casts = [
        'expires_at' => 'datetime',
        'used_at'    => 'datetime',
    ];

    // ── Relationships ─────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ── Code generation ───────────────────────────────────────

    /**
     * Generate a secure 8-character alphanumeric code.
     * Excludes visually ambiguous characters: 0, O, I, l, 1.
     * Characters are ALL UPPERCASE to avoid mixed-case confusion
     * when users type the code manually.
     */
    public static function generateCode(): string
    {
        // Uppercase-only charset — unambiguous characters only.
        // This also eliminates the paste-case-mismatch bug described above.
        $chars = 'ABCDEFGHJKMNPQRSTUVWXYZ23456789';
        $code  = '';

        for ($i = 0; $i < 8; $i++) {
            $code .= $chars[random_int(0, strlen($chars) - 1)];
        }

        return $code;
    }

    // ── Lifecycle helpers ─────────────────────────────────────

    /**
     * Atomically delete any existing codes and create a fresh one
     * for the given user, expiring in 10 minutes.
     */
    public static function issueFor(User $user): self
    {
        return DB::transaction(function () use ($user) {
            static::where('user_id', $user->id)->delete();

            return static::create([
                'user_id'    => $user->id,
                'code'       => static::generateCode(),
                'expires_at' => now()->addMinutes(10),
            ]);
        });
    }

    /**
     * Find a valid (non-expired, not yet used) code for a user.
     *
     * The incoming $code is uppercased to match the stored format
     * produced by generateCode(). This prevents false negatives when
     * the user's email client auto-lowercases copied text.
     */
    public static function findValid(int $userId, string $code): ?self
    {
        return static::where('user_id', $userId)
            ->where('code', strtoupper(trim($code)))
            ->where('expires_at', '>', now())
            ->whereNull('used_at')          // reject already-used codes
            ->first();
    }

    /**
     * Mark this code as consumed so it cannot be replayed.
     * Call this immediately after the user is marked as verified.
     */
    public function markAsUsed(): void
    {
        $this->update(['used_at' => now()]);
    }

    // ── Convenience checks ────────────────────────────────────

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    public function isUsed(): bool
    {
        return $this->used_at !== null;
    }
}
