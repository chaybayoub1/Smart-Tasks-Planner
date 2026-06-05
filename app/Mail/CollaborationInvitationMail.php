<?php

namespace App\Mail;

use App\Models\CollaborationGroupInvitation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class CollaborationInvitationMail extends Mailable
{
    use Queueable;

    public function __construct(
        public readonly CollaborationGroupInvitation $invitation,
        public readonly string $acceptUrl,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'You are invited to collaborate on ' . $this->invitation->group->name,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.collaboration-invitation',
            with: [
                'groupName' => $this->invitation->group->name,
                'groupDescription' => $this->invitation->group->description,
                'inviterName' => $this->invitation->inviter->name,
                'invitedEmail' => $this->invitation->email,
                'acceptUrl' => $this->acceptUrl,
                'expiresAt' => $this->invitation->expires_at,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
