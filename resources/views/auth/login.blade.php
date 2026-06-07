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
                <input id="password" type="password" name="password" required autocomplete="current-password"
                    placeholder="••••••••" class="glass-input @error('password') is-invalid @enderror">
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
