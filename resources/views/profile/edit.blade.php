{{-- resources/views/profile/edit.blade.php --}}
@extends('layouts.app')
@section('title', 'Profile')
@section('page-title', 'Profile')

@push('styles')
<style>
    .profile-hero {
        background: #fff;
        border: 1px solid #e9ecef;
        border-radius: 1rem;
        box-shadow: 0 12px 32px rgba(0,0,0,.05);
        padding: 1.25rem;
    }
    .hero-avatar {
        width: 88px;
        height: 88px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #e9ecef;
        box-shadow: 0 10px 30px rgba(0,0,0,.08);
    }
    .hero-avatar-placeholder {
        width: 88px;
        height: 88px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.6rem;
        font-weight: 800;
        color: #fff;
        background: linear-gradient(135deg, #6366f1, #4f46e5);
        border: 3px solid #e9ecef;
        box-shadow: 0 10px 30px rgba(0,0,0,.08);
    }
    .profile-section-title {
        display: flex;
        align-items: center;
        gap: .75rem;
        margin-bottom: 1rem;
    }
    .profile-section-title i {
        font-size: 1.2rem;
        color: #6366f1;
    }
    .avatar-preview-wrapper {
        width: 72px;
        height: 72px;
        border-radius: 50%;
        overflow: hidden;
        border: 2px solid #dee2e6;
        background: #f8f9ff;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .btn-study-method,
    .btn-theme-option {
        min-width: 115px;
    }
    .danger-card {
        border-color: rgba(220,53,69,.25) !important;
    }
</style>
@endpush

@section('content')

@php $user = auth()->user(); @endphp

{{-- ── HERO CARD ─────────────────────────────────────────────── --}}
<div class="row g-4">
    <div class="col-12">
        <div class="card profile-hero shadow-sm">
            <div class="card-body">
                <div class="d-flex flex-column flex-md-row align-items-center gap-4">
                    <div class="avatar-preview-wrapper">
                        @if($user->avatar)
                            <img src="{{ $user->avatarUrl() }}" alt="avatar" class="hero-avatar">
                        @else
                            <div class="hero-avatar-placeholder">{{ $user->initials() }}</div>
                        @endif
                    </div>
                    <div class="flex-grow-1">
                        <h2 class="h4 mb-1">{{ $user->name }}</h2>
                        <p class="text-muted mb-2">
                            @if($user->username) @{{ $user->username }} · @endif
                            Joined {{ $user->created_at->format('M Y') }}
                        </p>
                        @if($user->bio)
                            <p class="mb-2">{{ $user->bio }}</p>
                        @endif
                        <div class="d-flex flex-wrap gap-2">
                            <span class="badge bg-primary">{{ number_format($user->xp) }} XP</span>
                            <span class="badge bg-warning text-dark">Level {{ $user->level }}</span>
                            @if($user->university)
                                <span class="badge bg-secondary">{{ $user->university }}</span>
                            @endif
                            @if($user->field_of_study)
                                <span class="badge bg-secondary">{{ $user->field_of_study }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-xl-8">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="profile-section-title">
                    <i class="bi bi-person-circle"></i>
                    <h5 class="mb-0">Profile Information</h5>
                </div>

                @if(session('status') === 'profile-updated')
                    <div class="alert alert-success py-2">
                        <i class="bi bi-check-circle-fill me-1"></i> Profile updated successfully!
                    </div>
                @endif

                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                    @csrf @method('PATCH')

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Profile Photo</label>
                        <div class="d-flex flex-wrap align-items-center gap-3">
                            <div class="avatar-preview-wrapper">
                                @if($user->avatar)
                                    <img id="avatarPreview" src="{{ $user->avatarUrl() }}" alt="avatar" class="hero-avatar">
                                @else
                                    <div id="avatarPlaceholder" class="hero-avatar-placeholder">{{ $user->initials() }}</div>
                                    <img id="avatarPreview" src="" alt="" class="hero-avatar" style="display:none">
                                @endif
                            </div>
                            <div>
                                <input type="file" id="avatarFile" name="avatar" accept="image/*" hidden onchange="previewAvatar(this)">
                                <label for="avatarFile" class="btn btn-outline-secondary btn-sm mb-2">
                                    <i class="bi bi-cloud-arrow-up"></i> Upload photo
                                </label>
                                @if($user->avatar)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="removeAvatar" name="remove_avatar" value="1">
                                        <label class="form-check-label small" for="removeAvatar">
                                            Remove current photo
                                        </label>
                                    </div>
                                @endif
                                <p class="text-muted small mb-0">JPG, PNG or GIF · max 2MB</p>
                            </div>
                        </div>
                        @error('avatar') <div class="form-text text-danger">{{ $message }}</div> @enderror
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Full Name</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0"><i class="bi bi-person-fill"></i></span>
                                <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                            </div>
                            @error('name') <div class="form-text text-danger">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Username</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0"><i class="bi bi-at"></i></span>
                                <input type="text" name="username" class="form-control" value="{{ old('username', $user->username) }}" placeholder="janedoe99">
                            </div>
                            @error('username') <div class="form-text text-danger">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Email Address</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0"><i class="bi bi-envelope-fill"></i></span>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                        </div>
                        @error('email') <div class="form-text text-danger">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Bio <small class="text-muted">(optional)</small></label>
                        <textarea name="bio" class="form-control" rows="3" maxlength="300" placeholder="Tell others about you…">{{ old('bio', $user->bio) }}</textarea>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <h6 class="mb-3">Academic Info</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">University / School</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-building-fill"></i></span>
                                    <input type="text" name="university" class="form-control" value="{{ old('university', $user->university) }}" placeholder="MIT, Stanford…">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Academic Level</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-bar-chart-steps"></i></span>
                                    <select name="academic_level" class="form-select">
                                        <option value="">— Select —</option>
                                        @foreach(['high_school'=>'High School','engineering_cycle'=>'Engineering Cycle','bachelor'=>'Bachelor','master'=>'Master','phd'=>'PhD'] as $val => $lbl)
                                            <option value="{{ $val }}" {{ old('academic_level', $user->academic_level) == $val ? 'selected' : '' }}>{{ $lbl }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Field of Study / Major</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0"><i class="bi bi-journal-bookmark-fill"></i></span>
                            <input type="text" name="field_of_study" class="form-control" value="{{ old('field_of_study', $user->field_of_study) }}" placeholder="Computer Science, Medicine…">
                        </div>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <h6 class="mb-3">Study Preferences</h6>
                        <div class="d-flex flex-wrap gap-2">
                            @php
                                $methods = [
                                    ['value'=>'pomodoro','icon'=>'bi-stopwatch-fill','label'=>'Pomodoro'],
                                    ['value'=>'flashcards','icon'=>'bi-layers-fill','label'=>'Flashcards'],
                                    ['value'=>'task_planning','icon'=>'bi-check2-square','label'=>'Task Planning'],
                                    ['value'=>'notes','icon'=>'bi-journal-text','label'=>'Notes'],
                                    ['value'=>'exams','icon'=>'bi-calendar-event-fill','label'=>'Exams'],
                                ];
                                $currentMethods = old('study_methods', $user->study_methods ?? []);
                            @endphp
                            @foreach($methods as $m)
                                @php $checked = in_array($m['value'], $currentMethods); @endphp
                                <input type="checkbox" class="btn-check" id="method_{{ $m['value'] }}" autocomplete="off" name="study_methods[]" value="{{ $m['value'] }}" {{ $checked ? 'checked' : '' }}>
                                <label class="btn btn-outline-primary btn-study-method {{ $checked ? 'active' : '' }}" for="method_{{ $m['value'] }}">
                                    <i class="bi {{ $m['icon'] }} me-1"></i>{{ $m['label'] }}
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Daily Study Goal</label>
                        <div class="d-flex align-items-center gap-3">
                            <input type="range" name="study_goal" min="0.5" max="12" step="0.5" class="form-range" value="{{ old('study_goal', $user->study_goal ?? 2) }}" oninput="document.getElementById('goalVal').textContent = this.value + 'h'">
                            <div class="badge bg-secondary" id="goalVal">{{ old('study_goal', $user->study_goal ?? 2) }}h</div>
                        </div>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <h6 class="mb-3">App Settings</h6>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Theme</label>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach([['dark','bi-moon-stars-fill','Dark'],['light','bi-sun-fill','Light'],['system','bi-circle-half','System']] as $option)
                                    @php [$val,$ico,$lbl] = $option; $selected = old('theme', $user->theme ?? 'dark') == $val; @endphp
                                    <input type="radio" class="btn-check" name="theme" id="theme_{{ $val }}" value="{{ $val }}" autocomplete="off" {{ $selected ? 'checked' : '' }}>
                                    <label class="btn btn-outline-secondary btn-theme-option {{ $selected ? 'active' : '' }}" for="theme_{{ $val }}">
                                        <i class="bi {{ $ico }} me-1"></i>{{ $lbl }}
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Timezone</label>
                                <select name="timezone" class="form-select">
                                    @foreach(['UTC','Africa/Casablanca','Africa/Cairo','Europe/London','Europe/Paris','Europe/Berlin','America/New_York','America/Chicago','America/Los_Angeles','Asia/Dubai','Asia/Karachi','Asia/Kolkata','Asia/Tokyo','Australia/Sydney'] as $tz)
                                        <option value="{{ $tz }}" {{ old('timezone', $user->timezone) == $tz ? 'selected' : '' }}>{{ $tz }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Language</label>
                                <select name="language" class="form-select">
                                    @foreach(['en'=>'English','fr'=>'Français','ar'=>'العربية','es'=>'Español','de'=>'Deutsch','pt'=>'Português','zh'=>'中文'] as $code => $label)
                                        <option value="{{ $code }}" {{ old('language', $user->language) == $code ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save-fill me-1"></i> Save All Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-12 col-xl-4">
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="profile-section-title">
                    <i class="bi bi-lock-fill"></i>
                    <h5 class="mb-0">Change Password</h5>
                </div>

                @if(session('status') === 'password-updated')
                    <div class="alert alert-success py-2">
                        <i class="bi bi-check-circle-fill me-1"></i> Password updated successfully!
                    </div>
                @endif

                <form method="POST" action="{{ route('profile.password') }}">
                    @csrf @method('PUT')
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Current Password</label>
                        <input type="password" name="current_password" class="form-control" autocomplete="current-password">
                        @error('current_password') <div class="form-text text-danger">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">New Password</label>
                        <input type="password" name="password" class="form-control" autocomplete="new-password">
                        @error('password') <div class="form-text text-danger">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Confirm New Password</label>
                        <input type="password" name="password_confirmation" class="form-control" autocomplete="new-password">
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-shield-lock me-1"></i> Update Password
                    </button>
                </form>
            </div>
        </div>

        <div class="card shadow-sm danger-card">
            <div class="card-body">
                <div class="profile-section-title">
                    <i class="bi bi-exclamation-triangle-fill text-danger"></i>
                    <h5 class="mb-0 text-danger">Danger Zone</h5>
                </div>
                <p class="text-muted">Once deleted, all your data — tasks, notes, flashcards, sessions — will be permanently removed.</p>
                <button class="btn btn-outline-danger w-100 mb-3" type="button" data-bs-toggle="collapse" data-bs-target="#deleteForm">
                    <i class="bi bi-trash me-1"></i> Delete My Account
                </button>
                <div class="collapse" id="deleteForm">
                    <form method="POST" action="{{ route('profile.destroy') }}">
                        @csrf @method('DELETE')
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Confirm your password</label>
                            <input type="password" name="password" class="form-control" placeholder="Your current password">
                            @error('password', 'userDeletion') <div class="form-text text-danger">{{ $message }}</div> @enderror
                        </div>
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="bi bi-trash-fill me-1"></i> Yes, delete everything
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function previewAvatar(input) {
    if (!input.files?.[0]) return;
    const reader = new FileReader();
    reader.onload = e => {
        const prev = document.getElementById('avatarPreview');
        const plac = document.getElementById('avatarPlaceholder');
        if (prev) {
            prev.src = e.target.result;
            prev.style.display = 'block';
        }
        if (plac) {
            plac.style.display = 'none';
        }
    };
    reader.readAsDataURL(input.files[0]);
}
</script>
@endpush
