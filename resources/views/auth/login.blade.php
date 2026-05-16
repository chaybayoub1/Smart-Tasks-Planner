<x-guest-layout>

    {{-- Mobile brand mark (CSS hides this on desktop) --}}
    <div class="brand-mobile">
        🎓 Smart<span class="dot">Tasker</span>
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
            <span style="font-size:.72rem; color:rgba(255,255,255,.22);">🎓 SmartTasker &copy; {{ date('Y') }}</span>
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

</x-guest-layout>