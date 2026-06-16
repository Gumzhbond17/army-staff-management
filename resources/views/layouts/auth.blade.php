<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name'))</title>

    {{-- Bootstrap 5.3.8 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Google Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
            background: linear-gradient(135deg, #0f0c29, #302b63, #24243e);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
        }

        /* Ambient orbs */
        body::before,
        body::after {
            content: '';
            position: fixed;
            border-radius: 50%;
            filter: blur(80px);
            pointer-events: none;
            z-index: 0;
        }

        body::before {
            width: 420px;
            height: 420px;
            background: rgba(124, 58, 237, 0.45);
            top: -120px;
            left: -100px;
        }

        body::after {
            width: 380px;
            height: 380px;
            background: rgba(37, 99, 235, 0.4);
            bottom: -100px;
            right: -80px;
        }

        .auth-wrapper {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 420px;
            padding: 1rem;
        }

        /* Glass card */
        .glass-card {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 24px;
            padding: 2.5rem 2.25rem;
            box-shadow:
                0 8px 32px rgba(0, 0, 0, 0.4),
                inset 0 1px 0 rgba(255, 255, 255, 0.18);
        }

        /* Logo */
        .auth-logo-icon {
            width: 56px;
            height: 56px;
            background: linear-gradient(135deg, #7c3aed, #2563eb);
            border-radius: 15px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 20px rgba(124, 58, 237, 0.5);
            margin-bottom: 14px;
        }

        .auth-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #ffffff;
            letter-spacing: -0.3px;
        }

        .auth-subtitle {
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.45);
            margin-top: 4px;
        }

        /* Form controls */
        .glass-label {
            font-size: 0.7rem;
            font-weight: 500;
            color: rgba(255, 255, 255, 0.55);
            letter-spacing: 0.6px;
            text-transform: uppercase;
            margin-bottom: 7px;
        }

        .glass-input {
            width: 100%;
            padding: 11px 14px;
            background: rgba(255, 255, 255, 0.07) !important;
            border: 1px solid rgba(255, 255, 255, 0.12) !important;
            border-radius: 10px !important;
            color: #ffffff !important;
            font-size: 0.9rem;
            font-family: 'Plus Jakarta Sans', sans-serif;
            transition: border-color 0.2s, background 0.2s;
        }

        .glass-input::placeholder {
            color: rgba(255, 255, 255, 0.22);
        }

        .glass-input:focus {
            outline: none !important;
            box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.25) !important;
            border-color: rgba(124, 58, 237, 0.6) !important;
            background: rgba(255, 255, 255, 0.1) !important;
        }

        .glass-input.is-invalid {
            border-color: rgba(239, 68, 68, 0.5) !important;
        }

        .invalid-feedback {
            color: #fca5a5 !important;
            font-size: 0.78rem;
        }

        /* Remember / Forgot */
        .remember-label {
            font-size: 0.82rem;
            color: rgba(255, 255, 255, 0.45);
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .remember-label input[type="checkbox"] {
            accent-color: #7c3aed;
        }

        .forgot-link {
            font-size: 0.82rem;
            color: rgba(167, 139, 250, 0.9);
            text-decoration: none;
        }

        .forgot-link:hover {
            color: #a78bfa;
            text-decoration: underline;
        }

        /* Submit button */
        .btn-glass-primary {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #7c3aed, #2563eb);
            border: none;
            border-radius: 10px;
            color: #fff;
            font-size: 0.95rem;
            font-weight: 500;
            font-family: 'Plus Jakarta Sans', sans-serif;
            letter-spacing: 0.2px;
            cursor: pointer;
            box-shadow: 0 4px 18px rgba(124, 58, 237, 0.4);
            transition: opacity 0.2s, transform 0.15s;
        }

        .btn-glass-primary:hover {
            opacity: 0.88;
            transform: translateY(-1px);
        }

        .btn-glass-primary:active {
            transform: translateY(0);
        }

        /* Alerts */
        .glass-alert {
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 0.82rem;
            margin-bottom: 1.1rem;
        }

        .glass-alert-danger {
            background: rgba(239, 68, 68, 0.13);
            border: 1px solid rgba(239, 68, 68, 0.28);
            color: #fca5a5;
        }

        .glass-alert-success {
            background: rgba(34, 197, 94, 0.12);
            border: 1px solid rgba(34, 197, 94, 0.25);
            color: #86efac;
        }

        /* Divider */
        .glass-divider {
            text-align: center;
            position: relative;
            color: rgba(255, 255, 255, 0.2);
            font-size: 0.75rem;
            margin: 1.25rem 0;
        }

        .glass-divider::before,
        .glass-divider::after {
            content: '';
            position: absolute;
            top: 50%;
            width: 44%;
            height: 1px;
            background: rgba(255, 255, 255, 0.1);
        }

        .glass-divider::before {
            left: 0;
        }

        .glass-divider::after {
            right: 0;
        }

        /* Footer text */
        .auth-footer {
            text-align: center;
            font-size: 0.82rem;
            color: rgba(255, 255, 255, 0.32);
            margin-top: 1.25rem;
        }

        .auth-footer a {
            color: rgba(167, 139, 250, 0.9);
            text-decoration: none;
        }

        .auth-footer a:hover {
            text-decoration: underline;
        }
    </style>

    @stack('styles')
</head>

<body>
    <div class="auth-wrapper">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>

</html>
