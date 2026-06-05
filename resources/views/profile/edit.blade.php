{{-- resources/views/profile/edit.blade.php --}}
@extends('layouts.app')
@section('title', 'Profile')
@section('page-title', 'Profile')

@section('content')

<div class="row g-4" style="max-width:760px">

    {{-- ── UPDATE PROFILE INFO ──────────────────────────────── --}}
    <div class="col-12">
        <div class="st-card">
            <div class="st-card-header">
                <div class="st-card-title">
                    <div class="icon icon-teal"><i class="bi bi-person-fill"></i></div>
                    Profile Information
                </div>
            </div>
            <div style="padding:1.25rem">

                @if(session('status') === 'profile-updated')
                    <div class="st-alert st-alert-success" style="margin-bottom:1rem">
                        <i class="bi bi-check-circle-fill"></i> Profile updated successfully.
                    </div>
                @endif

                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf @method('PATCH')
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="st-label">Name</label>
                            <input type="text" name="name" class="st-input"
                                   value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <span style="font-size:.75rem;color:var(--c-coral);margin-top:.25rem;display:block">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="st-label">Email</label>
                            <input type="email" name="email" class="st-input"
                                   value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <span style="font-size:.75rem;color:var(--c-coral);margin-top:.25rem;display:block">{{ $message }}</span>
                            @enderror
                        </div>

                        @if($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                        <div class="col-12">
                            <div style="padding:.65rem .9rem;background:rgba(245,158,11,.1);border:1px solid rgba(245,158,11,.25);border-radius:8px;font-size:.82rem;color:var(--c-amber)">
                                <i class="bi bi-envelope-exclamation me-1"></i>
                                Your email address is unverified.
                                <form method="POST" action="{{ route('verification.send') }}" style="display:inline">
                                    @csrf
                                    <button type="submit" style="background:none;border:none;color:var(--c-teal);cursor:pointer;font-size:.82rem;padding:0;text-decoration:underline">
                                        Click here to re-send the verification email.
                                    </button>
                                </form>
                            </div>
                        </div>
                        @endif

                        <div class="col-12">
                            <button type="submit" class="btn-st-primary">
                                <i class="bi bi-save"></i> Save Changes
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ── UPDATE PASSWORD ───────────────────────────────────── --}}
    <div class="col-12">
        <div class="st-card">
            <div class="st-card-header">
                <div class="st-card-title">
                    <div class="icon icon-violet"><i class="bi bi-lock-fill"></i></div>
                    Update Password
                </div>
            </div>
            <div style="padding:1.25rem">

                @if(session('status') === 'password-updated')
                    <div class="st-alert st-alert-success" style="margin-bottom:1rem">
                        <i class="bi bi-check-circle-fill"></i> Password updated successfully.
                    </div>
                @endif

                <form method="POST" action="{{ route('password.update') }}">
                    @csrf @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="st-label">Current Password</label>
                            <input type="password" name="current_password" class="st-input" autocomplete="current-password">
                            @error('current_password', 'updatePassword')
                                <span style="font-size:.75rem;color:var(--c-coral);margin-top:.25rem;display:block">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="st-label">New Password</label>
                            <input type="password" name="password" class="st-input" autocomplete="new-password">
                            @error('password', 'updatePassword')
                                <span style="font-size:.75rem;color:var(--c-coral);margin-top:.25rem;display:block">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="st-label">Confirm Password</label>
                            <input type="password" name="password_confirmation" class="st-input" autocomplete="new-password">
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn-st-primary">
                                <i class="bi bi-shield-lock"></i> Update Password
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ── DELETE ACCOUNT ────────────────────────────────────── --}}
    <div class="col-12">
        <div class="st-card" style="border-color:rgba(255,107,107,.2)">
            <div class="st-card-header" style="border-color:rgba(255,107,107,.2)">
                <div class="st-card-title">
                    <div class="icon icon-coral"><i class="bi bi-exclamation-triangle-fill"></i></div>
                    Delete Account
                </div>
            </div>
            <div style="padding:1.25rem">
                <p style="font-size:.85rem;color:var(--c-muted2);margin-bottom:1rem">
                    Once your account is deleted, all data will be permanently removed. This action cannot be undone.
                </p>

                <button type="button" class="btn-st-danger" data-bs-toggle="collapse" data-bs-target="#deleteForm">
                    <i class="bi bi-trash"></i> Delete My Account
                </button>

                <div class="collapse" id="deleteForm" style="margin-top:1rem;padding-top:1rem;border-top:1px solid rgba(255,107,107,.15)">
                    <form method="POST" action="{{ route('profile.destroy') }}">
                        @csrf @method('DELETE')
                        <div style="max-width:340px">
                            <label class="st-label">Confirm your password to delete</label>
                            <input type="password" name="password" class="st-input"
                                   placeholder="Enter your password" style="margin-bottom:.75rem">
                            @error('password', 'userDeletion')
                                <span style="font-size:.75rem;color:var(--c-coral);margin-bottom:.5rem;display:block">{{ $message }}</span>
                            @enderror
                            <button type="submit" class="btn-st-danger"
                                    onclick="return confirm('Are you absolutely sure? This cannot be undone.')">
                                <i class="bi bi-trash-fill"></i> Yes, delete my account permanently
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection