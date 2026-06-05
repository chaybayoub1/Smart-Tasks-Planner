<?php

namespace App\Http\Controllers;

use App\Mail\CollaborationInvitationMail;
use App\Models\CollaborationGroup;
use App\Models\CollaborationGroupInvitation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Throwable;

class CollaborationInvitationController extends Controller
{
    public function store(Request $request, CollaborationGroup $group)
    {
        Gate::authorize('manageMembers', $group);

        $data = $request->validate([
            'email' => 'required|email|max:255',
        ]);

        $email = Str::lower($data['email']);
        $existingUser = User::where('email', $email)->first();

        if ($existingUser && $group->hasMember($existingUser)) {
            return back()->with('error', 'This user is already a member of the group.');
        }

        $invitation = CollaborationGroupInvitation::updateOrCreate(
            [
                'group_id' => $group->id,
                'email' => $email,
                'status' => 'pending',
            ],
            [
                'invited_by' => auth()->id(),
                'token' => Str::random(48),
                'expires_at' => now()->addDays(14),
            ]
        );

        $invitation->load(['group', 'inviter']);
        $acceptUrl = route('groups.invitations.respond', $invitation->token);

        try {
            Mail::to($email)->send(new CollaborationInvitationMail($invitation, $acceptUrl));

            return back()->with('success', 'Invitation sent to ' . $email . '.');
        } catch (Throwable $exception) {
            Log::warning('Collaboration invitation email failed.', [
                'email' => $email,
                'group_id' => $group->id,
                'error' => $exception->getMessage(),
            ]);

            return back()->with('error', 'Invitation created, but the email could not be sent. Share this link manually: ' . $acceptUrl);
        }
    }

    public function accept(string $token)
    {
        $invitation = CollaborationGroupInvitation::with('group')
            ->where('token', $token)
            ->firstOrFail();

        if (! $this->canRespond($invitation)) {
            return redirect()->route('groups.index')->with('error', 'This invitation is not available.');
        }

        $user = auth()->user();

        if (Str::lower($user->email) !== Str::lower($invitation->email)) {
            return redirect()->route('groups.index')->with('error', 'This invitation belongs to another email address.');
        }

        $invitation->group->members()->syncWithoutDetaching([
            $user->id => [
                'role' => 'member',
                'joined_at' => now(),
            ],
        ]);

        $invitation->update([
            'status' => 'accepted',
            'responded_at' => now(),
        ]);

        return redirect()->route('groups.show', $invitation->group)->with('success', 'Invitation accepted.');
    }

    public function decline(string $token)
    {
        $invitation = CollaborationGroupInvitation::where('token', $token)->firstOrFail();

        if (! $this->canRespond($invitation)) {
            return redirect()->route('groups.index')->with('error', 'This invitation is not available.');
        }

        if (Str::lower(auth()->user()->email) !== Str::lower($invitation->email)) {
            return redirect()->route('groups.index')->with('error', 'This invitation belongs to another email address.');
        }

        $invitation->update([
            'status' => 'declined',
            'responded_at' => now(),
        ]);

        return redirect()->route('groups.index')->with('success', 'Invitation declined.');
    }

    private function canRespond(CollaborationGroupInvitation $invitation): bool
    {
        return $invitation->status === 'pending'
            && (! $invitation->expires_at || $invitation->expires_at->isFuture());
    }
}
