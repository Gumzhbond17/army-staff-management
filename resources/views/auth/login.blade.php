@extends('layouts.auth')

@section('title', 'Sign In — ' . config('app.name'))

@section('content')
    <div class="glass-card">

        {{-- Logo --}}
        <div class="text-center mb-4">
            <div class="auth-logo-icon mx-auto">
                {{-- <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="none" viewBox="0 0 24 24"
                    stroke="white" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5" />
                </svg> --}}
                <img src="{{ asset('assets/images/lao-army-logo.png') }}" alt="logo" style="width: 45px; height: 45px;" />
            </div>
            <h1 class="auth-title">ຍິນດີຕ້ອນຮັບ</h1>
            <p class="auth-subtitle">ເຂົ້າສູ່ລະບົບບັນຊີຂອງທ່ານ</p>
        </div>

        {{-- Session / Error alerts --}}
        @if (session('status'))
            <div class="glass-alert glass-alert-success">
                ✓ {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="glass-alert glass-alert-danger">
                ✕ {{ $errors->first() }}
            </div>
        @endif

        {{-- Login form --}}
        <form method="POST" action="{{ route('login') }}" novalidate>
            @csrf

            {{-- Email --}}
            <div class="mb-3">
                <label class="glass-label" for="email">ອີເມວ</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required
                    autocomplete="email" autofocus placeholder="username@laoarmy.com"
                    class="glass-input @error('email') is-invalid @enderror">
                @error('email')
                    <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                @enderror
            </div>

            {{-- Password --}}
            <div class="mb-3">
                <label class="glass-label" for="password">ລະຫັດຜ່ານ</label>
                <div style="position: relative;">
                    <input id="password" type="password" name="password" required autocomplete="current-password"
                        placeholder="••••••••"
                        class="glass-input @error('password') is-invalid @enderror"
                        style="padding-right: 44px;">
                    <button type="button" id="togglePassword" aria-label="Toggle password visibility"
                        style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;padding:0;cursor:pointer;color:rgba(255,255,255,0.45);display:flex;align-items:center;">
                        {{-- Eye open (shown when password is hidden) --}}
                        <svg id="iconEyeOpen" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5s8.268 2.943 9.542 7c-1.274 4.057-5.065 7-9.542 7S3.732 16.057 2.458 12z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        {{-- Eye closed (hidden by default) --}}
                        <svg id="iconEyeClosed" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" style="display:none;">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.97 9.97 0 012.186-3.716M6.53 6.53A9.97 9.97 0 0112 5c4.477 0 8.268 2.943 9.542 7a9.97 9.97 0 01-4.12 5.317M6.53 6.53L3 3m3.53 3.53l11.94 11.94M17.47 17.47L21 21" />
                        </svg>
                    </button>
                </div>
                @error('password')
                    <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                @enderror
            </div>

            {{-- Remember me + Forgot --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                {{-- <label class="remember-label">
                    <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                    Remember me
                </label>
                @if (Route::has('password.request'))
                    <a class="forgot-link" href="{{ route('password.request') }}">Forgot password?</a>
                @endif --}}
            </div>

            <button type="submit" class="btn-glass-primary">
                ເຂົ້າລະບົບ
            </button>
        </form>

        {{-- @if (Route::has('register'))
            <div class="glass-divider">or</div>
            <div class="auth-footer">
                Don't have an account? <a href="{{ route('register') }}">Create one</a>
            </div>
        @endif --}}
    </div>
@endsection

@push('scripts')
<script>
    const toggleBtn = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    const iconOpen = document.getElementById('iconEyeOpen');
    const iconClosed = document.getElementById('iconEyeClosed');

    toggleBtn.addEventListener('click', function () {
        const isHidden = passwordInput.type === 'password';
        passwordInput.type = isHidden ? 'text' : 'password';
        iconOpen.style.display = isHidden ? 'none' : 'block';
        iconClosed.style.display = isHidden ? 'block' : 'none';
    });
</script>
@endpush
