<x-guest-layout>

{{-- ══════════════════════════════════════════════════════════
     REGISTER-ONLY LAYOUT OVERRIDES
     Strategy: widen the guest layout's panel-right to 560px
     and let it scroll naturally — identical structure to login,
     just a taller card.  NO horizontal overflow, NO fixed heights.
═══════════════════════════════════════════════════════════ --}}
<style>

    /* ══════════════════════════════════════════════════════════
       SINGLE-SCROLLBAR FIX
       Root cause: guest layout locks panel-right to
       max-height:100vh + overflow-y:auto, creating an internal
       scroll container independent of the page.
       Strategy:
         • body / html → height:auto, scroll freely
         • auth-wrapper → min-height only, never height-locked
         • panel-left  → sticky viewport column, clips x only
         • panel-right → overflow:clip (no scroll container,
                          no scrollbar, content simply visible)
         • card-glass  → height:auto, grows with content
    ══════════════════════════════════════════════════════════ */

    /* ── 1. Page root: free-scroll, hide decorative scrollbar ── */
    html {
        height: auto !important;
        overflow-y: scroll !important;          /* always reserve gutter space */
        overflow-x: hidden !important;
        scrollbar-width: none !important;       /* Firefox: hide bar */
        -ms-overflow-style: none !important;    /* IE/Edge legacy */
    }
    html::-webkit-scrollbar { display: none !important; }  /* Chrome/Safari */

    body {
        height: auto !important;
        min-height: 100vh !important;
        overflow: visible !important;
    }

    /* ── 2. Wrapper grows with content, never fixed-height ────── */
    .auth-wrapper {
        min-height: 100vh !important;
        height: auto !important;
        align-items: stretch !important;
    }

    /* ── 3. Left panel: sticky viewport column ─────────────────
       position:sticky + height:100vh keeps it anchored while the
       right form scrolls. overflow-x:clip stops horizontal bleed;
       overflow-y:visible lets the quote render past the boundary. */
    .panel-left {
        position: sticky !important;
        top: 0 !important;
        height: 100vh !important;
        overflow-x: clip !important;
        overflow-y: visible !important;
        justify-content: flex-start !important;
        padding-top: 2.5rem !important;
        align-self: flex-start !important;
    }

    /* ── 4. Right panel: NO internal scroll, ever ───────────────
       overflow:clip = content is clipped geometrically but NO
       scroll container is created → no scrollbar can appear.
       The panel itself is just as tall as its content; the PAGE
       scrollbar handles all vertical movement. */
    .panel-right {
        width: 100% !important;
        height: auto !important;
        min-height: 100vh !important;
        max-height: none !important;
        overflow: clip !important;              /* ← kills internal scrollbar */
        align-items: flex-start !important;
        align-self: stretch !important;
        padding: 2.5rem 1.5rem 3rem !important;
    }
    @media (min-width: 1024px) {
        .panel-right {
            width: 560px !important;
            flex-shrink: 0 !important;
            overflow: clip !important;
            max-height: none !important;
            align-items: flex-start !important;
            padding: 2.5rem 2rem 3rem !important;
        }
    }

    /* ── 5. form-card: natural width, no height constraints ───── */
    .form-card {
        width: 100% !important;
        max-width: 480px !important;
        height: auto !important;
        margin: 0 auto !important;
    }

    /* ── 6. card-glass: grows with content ─────────────────────  */
    .card-glass {
        height: auto !important;
        min-height: unset !important;
        max-height: none !important;
        overflow: visible !important;
        padding: 2rem 1.75rem 2.25rem !important;
    }

    /* ── 7. Tablet ──────────────────────────────────────────────  */
    @media (max-width: 1023px) {
        .panel-right {
            align-items: flex-start !important;
            padding: 2rem 1.5rem 3rem !important;
        }
        .form-card { max-width: 520px !important; }
    }

    /* ── 8. Mobile ─────────────────────────────────────────────  */
    @media (max-width: 640px) {
        .panel-right { padding: 1.25rem .75rem 2.5rem !important; }
        .form-card { max-width: 100% !important; }
        .card-glass { padding: 1.5rem 1.25rem 1.75rem !important; border-radius: 14px !important; }
        .field-row,
        .academic-row { grid-template-columns: 1fr !important; }
    }

    /* ════════════════════════════════════════════════════════
       SECTION HEADERS
    ════════════════════════════════════════════════════════ */
    .reg-section {
        margin-top: 1.4rem;
        margin-bottom: .75rem;
        padding-bottom: .4rem;
        border-bottom: 1px solid rgba(255,255,255,.09);
        display: flex;
        align-items: center;
        gap: .55rem;
    }
    .reg-section span {
        font-family: 'Sora', sans-serif;
        font-size: .68rem; font-weight: 700;
        letter-spacing: .1em; text-transform: uppercase;
        color: rgba(255,255,255,.38);
    }
    .reg-section i { font-size: .82rem; color: #818cf8; }

    /* ── Two-column grid for paired fields ───────────────────── */
    .field-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: .65rem;
        min-width: 0;
    }
    .field-row > * { min-width: 0; box-sizing: border-box; }
    @media (max-width: 600px) { .field-row { grid-template-columns: 1fr; } }

    /* Compact input groups — reduce vertical rhythm */
    .input-group-st { margin-bottom: .65rem; }
    .input-group-st label {
        display: block;
        font-size: .72rem;
        font-weight: 600;
        letter-spacing: .06em;
        text-transform: uppercase;
        color: rgba(255,255,255,.45);
        margin-bottom: .35rem;
    }

    /* ── Select & textarea styled to match inputs ─────────────── */
    .input-group-st select,
    .input-group-st textarea {
        width: 100%;
        padding: .65rem 1rem .65rem 2.5rem;
        background: rgba(255,255,255,.08);
        border: 1px solid rgba(255,255,255,.12);
        border-radius: 10px;
        color: #fff; font-size: .875rem; font-family: 'Inter', sans-serif;
        outline: none; appearance: none;
        transition: border-color .2s, background .2s, box-shadow .2s;
        box-sizing: border-box;
    }
    .input-group-st textarea {
        padding-left: 1rem;
        resize: vertical;
        min-height: 70px;
    }
    .input-group-st select option { background: #1e1b4b; color: #fff; }
    .input-group-st select:focus,
    .input-group-st textarea:focus {
        border-color: rgba(99,102,241,.7);
        background: rgba(99,102,241,.1);
        box-shadow: 0 0 0 3px rgba(99,102,241,.15);
    }

    /* Select chevron */
    .select-wrap { position: relative; }
    .select-wrap .select-chevron {
        position: absolute; right: .9rem; top: 50%; transform: translateY(-50%);
        color: rgba(255,255,255,.35); font-size: .8rem; pointer-events: none;
    }

    /* ── Chip group ──────────────────────────────────────────── */
    .chip-group {
        display: flex; flex-wrap: wrap; gap: .45rem;
        margin-top: .1rem;
    }
    .chip-label {
        display: flex; align-items: center; gap: .38rem;
        padding: .32rem .75rem;
        border: 1px solid rgba(255,255,255,.15);
        border-radius: 999px;
        font-size: .78rem; color: rgba(255,255,255,.6);
        cursor: pointer;
        transition: all .18s;
        user-select: none;
    }
    .chip-label input { display: none; }
    .chip-label:hover {
        border-color: rgba(99,102,241,.5);
        color: #c7d2fe;
        background: rgba(99,102,241,.1);
    }
    .chip-label.checked {
        border-color: #6366f1;
        background: rgba(99,102,241,.22);
        color: #a5b4fc;
    }

    /* ── Theme pills ─────────────────────────────────────────── */
    .theme-pills { display: flex; gap: .5rem; }
    .theme-pill {
        flex: 1; display: flex; flex-direction: column; align-items: center; gap: .25rem;
        padding: .5rem .4rem;
        border: 1.5px solid rgba(255,255,255,.12);
        border-radius: 10px;
        cursor: pointer;
        font-size: .73rem; color: rgba(255,255,255,.5);
        transition: all .18s;
        user-select: none;
    }
    .theme-pill i { font-size: 1rem; }
    .theme-pill:hover { border-color: rgba(99,102,241,.45); color: #c7d2fe; background: rgba(99,102,241,.08); }
    .theme-pill.selected { border-color: #6366f1; background: rgba(99,102,241,.2); color: #a5b4fc; }
    .theme-pill input { display: none; }

    /* ── Avatar uploader ─────────────────────────────────────── */
    .avatar-upload {
        display: flex; align-items: center; gap: .85rem;
        margin-bottom: .25rem;
    }
    .avatar-preview {
        width: 56px; height: 56px; border-radius: 50%;
        background: rgba(99,102,241,.2);
        border: 2px dashed rgba(99,102,241,.4);
        display: flex; align-items: center; justify-content: center;
        font-size: 1.4rem; color: #818cf8;
        overflow: hidden; flex-shrink: 0;
        transition: border-color .2s;
        cursor: pointer;
    }
    .avatar-preview:hover { border-color: #818cf8; }
    .avatar-preview img { width: 100%; height: 100%; object-fit: cover; }
    .avatar-upload label.upload-btn {
        display: inline-flex; align-items: center; gap: .4rem;
        padding: .4rem .85rem;
        background: rgba(255,255,255,.06);
        border: 1px solid rgba(255,255,255,.14);
        border-radius: 8px;
        font-size: .78rem; color: rgba(255,255,255,.6);
        cursor: pointer;
        transition: background .15s, color .15s;
        text-transform: none; letter-spacing: 0; font-weight: 400;
        margin-bottom: 0;
    }
    .avatar-upload label.upload-btn:hover { background: rgba(255,255,255,.12); color: #fff; }

    /* ── Password strength bar ───────────────────────────────── */
    .strength-bar-wrap {
        display: flex; gap: .25rem; margin-top: .4rem; height: 4px;
    }
    .strength-seg {
        flex: 1; border-radius: 99px;
        background: rgba(255,255,255,.1);
        transition: background .3s;
    }
    .strength-label {
        font-size: .68rem; margin-top: .28rem; transition: color .3s;
        color: rgba(255,255,255,.35); min-height: 1em;
    }

    /* ── Terms row ───────────────────────────────────────────── */
    .terms-row {
        display: flex; align-items: flex-start; gap: .6rem;
        margin-top: 1.25rem; margin-bottom: 1rem;
    }
    .terms-row input[type="checkbox"] {
        margin-top: 2px; width: 15px; height: 15px;
        accent-color: #6366f1; cursor: pointer; flex-shrink: 0;
    }
    .terms-row label {
        font-size: .8rem; color: rgba(255,255,255,.5);
        cursor: pointer; line-height: 1.5;
        text-transform: none; letter-spacing: 0; font-weight: 400; margin-bottom: 0;
    }
    .terms-row label a { color: #818cf8; text-decoration: none; }
    .terms-row label a:hover { color: #a5b4fc; text-decoration: underline; }

    /* ── Already-have-account link ───────────────────────────── */
    .already-link {
        text-align: center; margin-top: 1rem;
        font-size: .82rem; color: rgba(255,255,255,.38);
    }
    .already-link a {
        color: #818cf8; text-decoration: none; font-weight: 500;
        transition: color .15s;
    }
    .already-link a:hover { color: #a5b4fc; text-decoration: underline; }

    /* ── Register submit button ──────────────────────────────── */
    .btn-submit-reg {
        width: 100%; padding: .85rem 1.5rem;
        background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
        border: none; border-radius: 10px;
        color: #fff; font-family: 'Sora', sans-serif;
        font-size: .975rem; font-weight: 700; letter-spacing: .02em;
        cursor: pointer;
        display: flex; align-items: center; justify-content: center; gap: .55rem;
        transition: transform .15s, box-shadow .15s, filter .15s;
        box-shadow: 0 4px 24px rgba(99,102,241,.45);
    }
    .btn-submit-reg:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 30px rgba(99,102,241,.6);
        filter: brightness(1.08);
    }
    .btn-submit-reg:active { transform: translateY(0); }
    .btn-submit-reg:disabled {
        opacity: .5; cursor: not-allowed;
        transform: none; box-shadow: none; filter: none;
    }

    /* ── Range slider ────────────────────────────────────────── */
    .range-wrap { display: flex; align-items: center; gap: .7rem; }
    .range-wrap input[type="range"] {
        flex: 1; accent-color: #6366f1; cursor: pointer;
        height: 4px;
    }
    .range-val {
        min-width: 40px; text-align: center;
        font-size: .82rem; font-weight: 600;
        color: #a5b4fc; background: rgba(99,102,241,.15);
        border-radius: 6px; padding: .12rem .38rem;
    }

    /* ── Academic row alignment ──────────────────────────────── */
    .academic-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: .65rem;
        align-items: start;
        min-width: 0;
    }
    .academic-row > * { min-width: 0; box-sizing: border-box; }
    @media (max-width: 600px) {
        .academic-row { grid-template-columns: 1fr; }
    }
    .academic-row .input-group-st {
        display: flex;
        flex-direction: column;
    }
    .academic-row .input-group-st label {
        min-height: 1.6rem;
        display: flex;
        align-items: flex-end;
        margin-bottom: .35rem;
    }
    .academic-row .input-wrap,
    .academic-row .select-wrap {
        position: relative;
        display: flex;
        align-items: center;
        width: 100%;
        height: 2.625rem;
        box-sizing: border-box;
    }
    .academic-row .input-wrap input {
        height: 100%; width: 100%;
        box-sizing: border-box;
        padding: 0 1rem 0 2.5rem;
        background: rgba(255,255,255,.08);
        border: 1px solid rgba(255,255,255,.12);
        border-radius: 10px;
        color: #fff; font-size: .875rem; font-family: 'Inter', sans-serif;
        outline: none;
        transition: border-color .2s, background .2s, box-shadow .2s;
    }
    .academic-row .input-wrap input:focus {
        border-color: rgba(99,102,241,.7);
        background: rgba(99,102,241,.1);
        box-shadow: 0 0 0 3px rgba(99,102,241,.15);
    }
    .academic-row .input-wrap input::placeholder { color: rgba(255,255,255,.28); }
    .academic-row .select-wrap select {
        height: 100%; width: 100%;
        box-sizing: border-box;
        padding: 0 2.25rem 0 2.5rem;
        background: rgba(255,255,255,.08);
        border: 1px solid rgba(255,255,255,.12);
        border-radius: 10px;
        color: #fff; font-size: .875rem; font-family: 'Inter', sans-serif;
        outline: none; appearance: none; -webkit-appearance: none;
        cursor: pointer;
        transition: border-color .2s, background .2s, box-shadow .2s;
    }
    .academic-row .select-wrap select:focus {
        border-color: rgba(99,102,241,.7);
        background: rgba(99,102,241,.1);
        box-shadow: 0 0 0 3px rgba(99,102,241,.15);
    }
    .academic-row .select-wrap select option { background: #1e1b4b; color: #fff; }
    .academic-row .input-icon-left {
        position: absolute; left: .9rem; top: 50%; transform: translateY(-50%);
        color: rgba(255,255,255,.35); font-size: .9rem;
        pointer-events: none; z-index: 2; line-height: 1;
    }
    .academic-row .select-chevron {
        position: absolute; right: .85rem; top: 50%; transform: translateY(-50%);
        color: rgba(255,255,255,.35); font-size: .75rem;
        pointer-events: none; z-index: 2;
    }

    /* ── Page-level header spacing ───────────────────────────── */
    h2 { margin-top: 0; }
</style>

{{-- ── Mobile brand mark (hidden on desktop by guest layout) ── --}}
<div class="brand-mobile">
    🎓 Smart<span class="dot">Tasker</span>
</div>

<h2>Create your account</h2>
<p class="subtitle">Your personalised study workspace awaits ✨</p>

<form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" id="registerForm">
    @csrf

    {{-- ════════════════════════════════════════════════════════
         SECTION 1 — PROFILE
    ═══════════════════════════════════════════════════════════ --}}
    <div class="reg-section" style="margin-top:0;">
        <i class="bi bi-person-circle"></i>
        <span>Your Profile</span>
    </div>

    {{-- Avatar --}}
    <div class="avatar-upload" style="margin-bottom:1rem;">
        <div class="avatar-preview" id="avatarPreview" onclick="document.getElementById('avatarInput').click()">
            <i class="bi bi-person-fill" id="avatarPlaceholder"></i>
            <img id="avatarImg" src="" alt="" style="display:none;">
        </div>
        <div>
            <label for="avatarInput" class="upload-btn">
                <i class="bi bi-cloud-arrow-up"></i> Upload photo
            </label>
            <input type="file" id="avatarInput" name="avatar" accept="image/*" style="display:none;">
            <p style="font-size:.72rem;color:rgba(255,255,255,.28);margin-top:.35rem;">JPG, PNG or GIF · max 2 MB</p>
        </div>
    </div>

    {{-- Name + Username --}}
    <div class="field-row">
        <div class="input-group-st">
            <label for="name">Full Name</label>
            <div class="input-wrap">
                <i class="bi bi-person-fill input-icon-left"></i>
                <input id="name" type="text" name="name" value="{{ old('name') }}"
                    placeholder="Jane Doe" required autofocus autocomplete="name"
                    class="{{ $errors->has('name') ? 'is-error' : '' }}">
            </div>
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>
        <div class="input-group-st">
            <label for="username">Username</label>
            <div class="input-wrap">
                <i class="bi bi-at input-icon-left"></i>
                <input id="username" type="text" name="username" value="{{ old('username') }}"
                    placeholder="janedoe99" required autocomplete="username"
                    class="{{ $errors->has('username') ? 'is-error' : '' }}">
            </div>
            <x-input-error :messages="$errors->get('username')" class="mt-2" />
        </div>
    </div>

    {{-- Email --}}
    <div class="input-group-st">
        <label for="email">Email Address</label>
        <div class="input-wrap">
            <i class="bi bi-envelope-fill input-icon-left"></i>
            <input id="email" type="email" name="email" value="{{ old('email') }}"
                placeholder="you@university.edu" required autocomplete="email"
                class="{{ $errors->has('email') ? 'is-error' : '' }}">
        </div>
        <x-input-error :messages="$errors->get('email')" class="mt-2" />
    </div>

    {{-- Password --}}
    <div class="input-group-st">
        <label for="password">Password</label>
        <div class="input-wrap">
            <i class="bi bi-lock-fill input-icon-left"></i>
            <input id="password" type="password" name="password"
                placeholder="Min 8 characters" required autocomplete="new-password"
                class="{{ $errors->has('password') ? 'is-error' : '' }}"
                oninput="checkStrength(this.value)">
            <button type="button" class="input-icon-right" id="pwToggle1" aria-label="Toggle password">
                <i class="bi bi-eye-fill" id="pwIcon1"></i>
            </button>
        </div>
        {{-- Strength meter --}}
        <div class="strength-bar-wrap" id="strengthBar">
            <div class="strength-seg" id="seg1"></div>
            <div class="strength-seg" id="seg2"></div>
            <div class="strength-seg" id="seg3"></div>
            <div class="strength-seg" id="seg4"></div>
        </div>
        <div class="strength-label" id="strengthLabel"></div>
        <x-input-error :messages="$errors->get('password')" class="mt-2" />
    </div>

    {{-- Confirm Password --}}
    <div class="input-group-st">
        <label for="password_confirmation">Confirm Password</label>
        <div class="input-wrap">
            <i class="bi bi-shield-lock-fill input-icon-left"></i>
            <input id="password_confirmation" type="password" name="password_confirmation"
                placeholder="Repeat password" required autocomplete="new-password"
                class="{{ $errors->has('password_confirmation') ? 'is-error' : '' }}">
            <button type="button" class="input-icon-right" id="pwToggle2" aria-label="Toggle confirm password">
                <i class="bi bi-eye-fill" id="pwIcon2"></i>
            </button>
        </div>
        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
    </div>


    {{-- ════════════════════════════════════════════════════════
         SECTION 2 — ACADEMIC INFO
    ═══════════════════════════════════════════════════════════ --}}
    <div class="reg-section">
        <i class="bi bi-mortarboard-fill"></i>
        <span>Academic Info</span>
    </div>

    <div class="academic-row">
        {{-- University --}}
        <div class="input-group-st">
            <label for="university">University / School</label>
            <div class="input-wrap">
                <i class="bi bi-building-fill input-icon-left"></i>
                <input id="university" type="text" name="university" value="{{ old('university') }}"
                    placeholder="MIT, Stanford…">
            </div>
        </div>
        {{-- Academic Level --}}
        <div class="input-group-st">
            <label for="academic_level">Academic Level</label>
            <div class="select-wrap">
                <i class="bi bi-bar-chart-steps input-icon-left"></i>
                <select id="academic_level" name="academic_level">
                    <option value="" disabled {{ old('academic_level') ? '' : 'selected' }}>Select level</option>
                    <option value="high_school"        {{ old('academic_level')=='high_school'        ? 'selected':'' }}>High School</option>
                    <option value="engineering_cycle"  {{ old('academic_level')=='engineering_cycle'  ? 'selected':'' }}>Engineering Cycle</option>
                    <option value="bachelor"           {{ old('academic_level')=='bachelor'           ? 'selected':'' }}>Bachelor</option>
                    <option value="master"             {{ old('academic_level')=='master'             ? 'selected':'' }}>Master</option>
                    <option value="phd"                {{ old('academic_level')=='phd'                ? 'selected':'' }}>PhD</option>
                </select>
                <i class="bi bi-chevron-down select-chevron"></i>
            </div>
        </div>
    </div>

    {{-- Field of Study --}}
    <div class="input-group-st">
        <label for="field_of_study">Field of Study / Major</label>
        <div class="input-wrap">
            <i class="bi bi-journal-bookmark-fill input-icon-left"></i>
            <input id="field_of_study" type="text" name="field_of_study" value="{{ old('field_of_study') }}"
                placeholder="Computer Science, Medicine…">
        </div>
    </div>


    {{-- ════════════════════════════════════════════════════════
         SECTION 3 — STUDY PREFERENCES
    ═══════════════════════════════════════════════════════════ --}}
    <div class="reg-section">
        <i class="bi bi-lightning-charge-fill"></i>
        <span>Study Preferences</span>
    </div>

    {{-- Preferred Study Methods --}}
    <div class="input-group-st">
        <label>Preferred Study Methods <span style="font-size:.65rem;opacity:.5;text-transform:none;letter-spacing:0;">(pick any)</span></label>
        <div class="chip-group" id="methodChips">
            @php
                $methods = [
                    ['value'=>'pomodoro',     'icon'=>'bi-stopwatch-fill',      'label'=>'Pomodoro'],
                    ['value'=>'flashcards',   'icon'=>'bi-layers-fill',          'label'=>'Flashcards'],
                    ['value'=>'task_planning','icon'=>'bi-check2-square',        'label'=>'Task Planning'],
                    ['value'=>'notes',        'icon'=>'bi-journal-text',         'label'=>'Notes'],
                    ['value'=>'exams',        'icon'=>'bi-calendar-event-fill',  'label'=>'Exams Tracking'],
                ];
                $oldMethods = old('study_methods', []);
            @endphp
            @foreach($methods as $m)
                <label class="chip-label {{ in_array($m['value'], $oldMethods) ? 'checked' : '' }}">
                    <input type="checkbox" name="study_methods[]" value="{{ $m['value'] }}"
                        {{ in_array($m['value'], $oldMethods) ? 'checked' : '' }}>
                    <i class="bi {{ $m['icon'] }}" style="font-size:.85rem;"></i>
                    <span>{{ $m['label'] }}</span>
                </label>
            @endforeach
        </div>
    </div>

    {{-- Daily Study Goal --}}
    <div class="input-group-st">
        <label for="study_goal">Daily Study Goal</label>
        <div class="range-wrap">
            <input type="range" id="study_goal" name="study_goal"
                min="0.5" max="12" step="0.5" value="{{ old('study_goal', 2) }}"
                oninput="document.getElementById('studyGoalVal').textContent=this.value+'h'">
            <div class="range-val" id="studyGoalVal">{{ old('study_goal', 2) }}h</div>
        </div>
    </div>


    {{-- ════════════════════════════════════════════════════════
         SECTION 4 — APP SETTINGS
    ═══════════════════════════════════════════════════════════ --}}
    <div class="reg-section">
        <i class="bi bi-sliders"></i>
        <span>App Settings</span>
    </div>

    {{-- Theme --}}
    <div class="input-group-st">
        <label>Preferred Theme</label>
        <div class="theme-pills">
            @foreach([['dark','bi-moon-stars-fill','Dark'],['light','bi-sun-fill','Light'],['system','bi-circle-half','System']] as [$val,$ico,$lbl])
                <label class="theme-pill {{ old('theme','dark')==$val ? 'selected' : '' }}" data-val="{{ $val }}">
                    <input type="radio" name="theme" value="{{ $val }}"
                        {{ old('theme','dark')==$val ? 'checked' : '' }}>
                    <i class="bi {{ $ico }}"></i>
                    {{ $lbl }}
                </label>
            @endforeach
        </div>
    </div>

    {{-- Timezone + Language --}}
    <div class="field-row">
        <div class="input-group-st">
            <label for="timezone">Timezone</label>
            <div class="select-wrap">
                <i class="bi bi-globe input-icon-left" style="position:absolute;top:50%;transform:translateY(-50%);left:.9rem;color:rgba(255,255,255,.35);pointer-events:none;z-index:2;"></i>
                <select id="timezone" name="timezone">
                    @php
                        $tz = old('timezone', 'UTC');
                        $zones = ['UTC','Africa/Casablanca','Africa/Cairo','Europe/London','Europe/Paris','Europe/Berlin',
                            'America/New_York','America/Chicago','America/Los_Angeles',
                            'Asia/Dubai','Asia/Karachi','Asia/Kolkata','Asia/Tokyo','Australia/Sydney'];
                    @endphp
                    @foreach($zones as $zone)
                        <option value="{{ $zone }}" {{ $tz==$zone ? 'selected':'' }}>{{ $zone }}</option>
                    @endforeach
                </select>
                <i class="bi bi-chevron-down select-chevron"></i>
            </div>
        </div>
        <div class="input-group-st">
            <label for="language">Language</label>
            <div class="select-wrap">
                <i class="bi bi-translate input-icon-left" style="position:absolute;top:50%;transform:translateY(-50%);left:.9rem;color:rgba(255,255,255,.35);pointer-events:none;z-index:2;"></i>
                <select id="language" name="language">
                    @php
                        $langs = ['en'=>'English','fr'=>'Français','ar'=>'العربية','es'=>'Español','de'=>'Deutsch','pt'=>'Português','zh'=>'中文'];
                        $lang = old('language','en');
                    @endphp
                    @foreach($langs as $code=>$label)
                        <option value="{{ $code }}" {{ $lang==$code ? 'selected':'' }}>{{ $label }}</option>
                    @endforeach
                </select>
                <i class="bi bi-chevron-down select-chevron"></i>
            </div>
        </div>
    </div>


    {{-- ════════════════════════════════════════════════════════
         TERMS + SUBMIT
    ═══════════════════════════════════════════════════════════ --}}
    <div class="terms-row">
        <input type="checkbox" id="terms" name="terms" required {{ old('terms') ? 'checked':'' }}>
        <label for="terms">
            I agree to the <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>.<br>
            <span style="font-size:.75rem;color:rgba(255,255,255,.3);">Your data is never sold or shared with third parties.</span>
        </label>
    </div>

    <button type="submit" class="btn-submit-reg" id="submitBtn">
        <i class="bi bi-rocket-takeoff-fill"></i>
        Launch my SmartTasker
    </button>

    <div class="already-link">
        Already have an account?
        <a href="{{ route('login') }}">Sign in →</a>
    </div>

</form>


{{-- ══════════════════════════════════════════════════════════
     JAVASCRIPT
═══════════════════════════════════════════════════════════ --}}
<script>
(function () {

    /* ── Password show/hide toggles ─────────────────────────── */
    function makeToggle(btnId, inputId, iconId) {
        var btn   = document.getElementById(btnId);
        var input = document.getElementById(inputId);
        var icon  = document.getElementById(iconId);
        if (!btn) return;
        btn.addEventListener('click', function () {
            var showing = input.type === 'text';
            input.type  = showing ? 'password' : 'text';
            icon.style.opacity = '0';
            setTimeout(function () {
                icon.className = showing ? 'bi bi-eye-fill' : 'bi bi-eye-slash-fill';
                icon.style.opacity = '1';
            }, 110);
            btn.classList.toggle('active', !showing);
            input.focus();
        });
        icon.style.transition = 'opacity .11s ease';
    }
    makeToggle('pwToggle1', 'password',             'pwIcon1');
    makeToggle('pwToggle2', 'password_confirmation','pwIcon2');

    /* ── Password strength meter ────────────────────────────── */
    window.checkStrength = function (val) {
        var score = 0;
        if (val.length >= 8)  score++;
        if (val.length >= 12) score++;
        if (/[A-Z]/.test(val) && /[a-z]/.test(val)) score++;
        if (/[0-9]/.test(val))  score++;
        if (/[^A-Za-z0-9]/.test(val)) score++;
        score = Math.min(score, 4);

        var colors  = ['#ef4444','#f97316','#eab308','#22c55e'];
        var labels  = ['Too weak','Fair','Good','Strong 💪'];
        var lColors = ['rgba(239,68,68,.7)','rgba(249,115,22,.7)','rgba(234,179,8,.7)','rgba(34,197,94,.7)'];

        var segs = document.querySelectorAll('.strength-seg');
        segs.forEach(function (s, i) {
            s.style.background = i < score ? colors[score - 1] : 'rgba(255,255,255,.1)';
        });
        var lbl = document.getElementById('strengthLabel');
        lbl.textContent = val.length ? labels[score - 1] || '' : '';
        lbl.style.color = val.length ? lColors[score - 1] || 'transparent' : 'transparent';
    };

    /* ── Chip checkboxes ────────────────────────────────────── */
    document.querySelectorAll('#methodChips .chip-label').forEach(function (label) {
        var cb = label.querySelector('input[type="checkbox"]');
        label.addEventListener('click', function () {
            setTimeout(function () {
                label.classList.toggle('checked', cb.checked);
            }, 0);
        });
    });

    /* ── Theme pills ────────────────────────────────────────── */
    document.querySelectorAll('.theme-pill').forEach(function (pill) {
        pill.addEventListener('click', function () {
            document.querySelectorAll('.theme-pill').forEach(function (p) { p.classList.remove('selected'); });
            pill.classList.add('selected');
        });
    });

    /* ── Avatar preview ─────────────────────────────────────── */
    document.getElementById('avatarInput').addEventListener('change', function () {
        var file = this.files[0];
        if (!file) return;
        var reader = new FileReader();
        reader.onload = function (e) {
            var img = document.getElementById('avatarImg');
            img.src = e.target.result;
            img.style.display = 'block';
            document.getElementById('avatarPlaceholder').style.display = 'none';
        };
        reader.readAsDataURL(file);
    });

})();
</script>

</x-guest-layout>