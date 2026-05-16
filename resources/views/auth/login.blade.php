<x-guest-layout>

    {{-- Mobile brand mark (CSS hides this on desktop) --}}
    <div class="brand-mobile">
        🎓 Smar<span class="dot">Tasker</span>
    </div>

    <h2>Welcome back</h2>
    <p class="subtitle">Sign in to continue your learning streak</p>

    {{-- Session Status --}}
    @if (session('status'))
        <div class="status-msg">
            <i class="bi bi-info-circle"></i> {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        {{-- ── Email ── --}}
        <div class="input-group-st">
            <label for="email">Email address</label>
            <div class="input-wrap">
                <i class="bi bi-envelope-fill input-icon-left"></i>
                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="you@university.edu"
                    required
                    autofocus
                    autocomplete="username"
                    class="{{ $errors->has('email') ? 'is-error' : '' }}"
                >
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        {{-- ── Password with show/hide toggle ── --}}
        <div class="input-group-st">
            <label for="password">Password</label>
            <div class="input-wrap">
                <i class="bi bi-lock-fill input-icon-left"></i>
                <input
                    id="password"
                    type="password"
                    name="password"
                    placeholder="••••••••"
                    required
                    autocomplete="current-password"
                    class="{{ $errors->has('password') ? 'is-error' : '' }}"
                >
                {{-- Eye toggle — sits flush right inside the input row --}}
                <button
                    type="button"
                    id="pwToggle"
                    class="input-icon-right"
                    aria-label="Toggle password visibility"
                    title="Show / hide password"
                >
                    <i class="bi bi-eye-fill" id="pwToggleIcon"></i>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        {{-- ── Remember me + Forgot password ── --}}
        <div class="row-between">
            <div class="checkbox-wrap">
                <input id="remember_me" type="checkbox" name="remember">
                <label for="remember_me">Remember me</label>
            </div>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="link-muted">
                    Forgot password?
                </a>
            @endif
        </div>

        {{-- ── Login button ── --}}
        <button type="submit" class="btn-primary-st">
            <i class="bi bi-box-arrow-in-right"></i>
            Sign In
        </button>

        {{-- ── Divider ── --}}
        <div class="divider-or"><span>or</span></div>

        {{-- ── Create account ── --}}
        @if (Route::has('register'))
            <a href="{{ route('register') }}" class="btn-register-st">
                <i class="bi bi-person-plus-fill"></i>
                Create a free account
            </a>
        @endif

        {{-- ── Footer ── --}}
        <div style="text-align:center; margin-top:1.4rem;">
            <span style="font-size:.72rem; color:rgba(255,255,255,.22);">🎓 SmarTasker &copy; {{ date('Y') }}</span>
        </div>

    </form>

    {{-- Password toggle — plain JS, no dependencies --}}
    <script>
        (function () {
            var btn   = document.getElementById('pwToggle');
            var input = document.getElementById('password');
            var icon  = document.getElementById('pwToggleIcon');
            if (!btn || !input || !icon) return;

            btn.addEventListener('click', function () {
                var showing = input.type === 'text';
                input.type  = showing ? 'password' : 'text';

                /* swap icon with a tiny fade for polish */
                icon.style.opacity = '0';
                icon.style.transform = 'scale(0.8)';
                setTimeout(function () {
                    icon.className = showing ? 'bi bi-eye-fill' : 'bi bi-eye-slash-fill';
                    icon.style.opacity = '1';
                    icon.style.transform = 'scale(1)';
                }, 120);

                btn.classList.toggle('active', !showing);
                /* keep focus on the field for accessibility */
                input.focus();
            });

            /* smooth icon transition */
            icon.style.transition = 'opacity .12s ease, transform .12s ease';
        })();
    </script>

{{-- ══════════════════════════════════════════════════════════
     LOGIN-PAGE LAYOUT OVERRIDES  (mirrors register_blade.php)
     Strategy: free-scroll page, sticky left panel, overflow:clip
     right panel so no internal scrollbar ever appears.
     The quote lives below the feature list on the left — visible
     after the user scrolls past the login card height.
     IntersectionObserver fades it in on first view.
═══════════════════════════════════════════════════════════ --}}
<style>

    /* ══════════════════════════════════════════════════════════
       SINGLE-SCROLLBAR FIX  (identical strategy to register)
    ══════════════════════════════════════════════════════════ */

    /* ── 1. Page root: free-scroll, hide decorative scrollbar ── */
    html {
        height: auto !important;
        overflow-y: scroll !important;
        overflow-x: hidden !important;
        scrollbar-width: none !important;
        -ms-overflow-style: none !important;
    }
    html::-webkit-scrollbar { display: none !important; }

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

    /* ── 3. Left panel: sticky viewport column ──────────────────
       Stays anchored while the right side (if ever taller) scrolls.
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
       overflow:clip = content clipped geometrically; no scroll
       container created, no scrollbar can appear. */
    .panel-right {
        width: 100% !important;
        height: auto !important;
        min-height: 100vh !important;
        max-height: none !important;
        overflow: clip !important;
        align-items: center !important;
        align-self: stretch !important;
        padding: 2.5rem 1.5rem 3rem !important;
    }
    @media (min-width: 1024px) {
        .panel-right {
            width: 520px !important;
            flex-shrink: 0 !important;
            overflow: clip !important;
            max-height: none !important;
            align-items: center !important;
            padding: 2.5rem 2rem 3rem !important;
        }
    }

    /* ── 5. form-card: natural width, centred ───────────────── */
    .form-card {
        width: 100% !important;
        max-width: 460px !important;
        height: auto !important;
        margin: 0 auto !important;
    }

    /* ── 6. card-glass: height auto, grows with content ────────  */
    .card-glass {
        height: auto !important;
        min-height: unset !important;
        max-height: none !important;
        overflow: visible !important;
        padding: 2rem 1.75rem 2.25rem !important;
    }

    /* ── 7. Tablet ────────────────────────────────────────────── */
    @media (max-width: 1023px) {
        .panel-right {
            align-items: center !important;
            padding: 2rem 1.5rem 3rem !important;
        }
        .form-card { max-width: 480px !important; }
    }

    /* ── 8. Mobile ────────────────────────────────────────────── */
    @media (max-width: 640px) {
        .panel-right { padding: 1.25rem .75rem 2.5rem !important; }
        .form-card { max-width: 100% !important; }
        .card-glass { padding: 1.5rem 1.25rem 1.75rem !important; border-radius: 14px !important; }
    }

    </style>



</x-guest-layout>