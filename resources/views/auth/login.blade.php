<x-guest-layout>
    <div class="login-wrapper">
        <div class="glass-card">

            {{-- Branding --}}
            <div class="login-brand">
                <div class="login-logo">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <div class="login-title">Welcome Back</div>
                <span class="login-title-line"></span>
                <div class="login-subtitle">Sign in to your LMS account</div>
            </div>

            {{-- Session Status --}}
            @if (session('status'))
            <div class="login-session-status">
                <i class="fas fa-check-circle me-1"></i> {{ session('status') }}
            </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                {{-- Email --}}
                <div class="form-group">
                    <label class="form-label" for="email">Email Address</label>
                    <div class="input-wrapper">
                        <i class="fas fa-envelope input-icon"></i>
                        <input id="email" class="form-input" type="email" name="email" value="{{ old('email') }}"
                            placeholder="you@example.com" required autofocus autocomplete="username">
                        <span class="input-glow"></span>
                    </div>
                    @error('email')
                    <div class="login-error"><i class="fas fa-circle-exclamation"></i> {{ $message }}</div>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="form-group">
                    <label class="form-label" for="password">Password</label>
                    <div class="input-wrapper">
                        <i class="fas fa-lock input-icon"></i>
                        <input id="password" class="form-input" type="password" name="password"
                            placeholder="Enter your password" required autocomplete="current-password">
                        <span class="input-glow"></span>
                        <button type="button" class="password-toggle" tabindex="-1">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    @error('password')
                    <div class="login-error"><i class="fas fa-circle-exclamation"></i> {{ $message }}</div>
                    @enderror
                </div>

                {{-- Remember / Forgot --}}
                <div class="login-options">
                    <label class="remember-label">
                        <input type="checkbox" name="remember" class="remember-checkbox" {{ old('remember') ? 'checked'
                            : '' }}>
                        Remember me
                    </label>
                    @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="forgot-link">Forgot password?</a>
                    @endif
                </div>

                {{-- Submit --}}
                <div class="login-btn-wrap">
                    <button type="submit" class="login-btn">
                        <span class="btn-text">Sign In <i class="fas fa-arrow-right"></i></span>
                    </button>
                </div>
            </form>

            {{-- Register link --}}
            @if (Route::has('register'))
            <div class="login-divider"><span>or</span></div>
            <div class="login-footer">
                <p>Don't have an account? <a href="{{ route('register') }}">Create one</a></p>
            </div>
            @endif

        </div>
    </div>

    {{-- Loading Overlay --}}
    <div class="login-loading-overlay" id="loginLoadingOverlay">
        <div class="loading-spinner"></div>
        <div class="loading-text">Signing you in<span class="loading-dots"></span></div>
    </div>
</x-guest-layout>