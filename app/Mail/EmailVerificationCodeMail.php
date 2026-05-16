<?php
// app/Mail/EmailVerificationCodeMail.php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

/**
 * ROOT-CAUSE FIX: SerializesModels REMOVED
 * ──────────────────────────────────────────────────────────────
 * The original class used both Queueable AND SerializesModels.
 * SerializesModels replaces Eloquent models in the job payload with
 * their primary key and re-fetches them from the database when the
 * queued job is processed. This creates two silent failure modes:
 *
 *   a) If the mail is sent synchronously (Mail::send, not Mail::queue),
 *      SerializesModels still runs __sleep()/__wakeup() cycles that can
 *      strip model attributes (including $user->name, $user->email) if
 *      the model was not fully hydrated at the time of construction —
 *      resulting in a blank "Hello,  👋" or, worse, a null-property
 *      exception that swallows the entire send attempt.
 *
 *   b) If the MAIL_MAILER queue driver is used and the worker picks up
 *      the job before the DB transaction for the new user is committed,
 *      it re-fetches an ID that doesn't exist yet → ModelNotFoundException
 *      → job fails silently.
 *
 * Since we send synchronously (Mail::to()->send()), Queueable is kept
 * for future flexibility but SerializesModels is dropped entirely.
 * The $user object is passed as-is and is always fully hydrated by the
 * time the Mailable constructor runs.
 *
 * ALSO FIXED: attachments() method added (required by Mailable contract
 * in Laravel 9+). Without it some versions throw a fatal
 * "must implement abstract method" error during rendering.
 * ──────────────────────────────────────────────────────────────
 */
class EmailVerificationCodeMail extends Mailable
{
    use Queueable;
    // SerializesModels intentionally removed — see explanation above.

    /**
     * @param  User   $user  The newly-registered user (fully hydrated).
     * @param  string $code  The raw 8-character verification code.
     */
    public function __construct(
        public readonly User   $user,
        public readonly string $code,
    ) {}

    /**
     * The email's envelope (To/Subject/Reply-To headers).
     *
     * NOTE: The code is intentionally NOT included in the subject line in
     * production — some spam filters flag emails where the subject contains
     * an alphanumeric token that also appears in the body.  Remove it if
     * deliverability becomes an issue.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Verify your SmarTasker account',
        );
    }

    /**
     * The email's content — points to resources/views/emails/verification-code.blade.php.
     *
     * Variables passed explicitly via `with` so the Blade template is
     * not solely reliant on public property auto-exposure (which can
     * behave differently across Laravel minor versions).
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.verification-code',
            with: [
                'userName'  => $this->user->name,   // string — avoids passing full model into Blade
                'userEmail' => $this->user->email,  // string — for display in email footer
                'code'      => $this->code,
                'expiresIn' => 10,
            ],
        );
    }

    /**
     * Required by the Mailable contract in Laravel 9+.
     * Return an empty array — no attachments.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
