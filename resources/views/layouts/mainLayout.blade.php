<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ASM</title>

    {{-- CSS only --}}
    @vite(['resources/css/app.css'])

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

    <style>
        /* Navbar background */
        .navbar {
            background: linear-gradient(135deg, #1e1b4b 0%, #312e81 100%) !important;
            border-bottom: 1px solid rgba(99, 102, 241, 0.4);
        }

        /* Brand */
        .navbar-brand {
            color: #fff !important;
            font-weight: 500;
            letter-spacing: 0.3px;
        }

        .navbar-brand:hover {
            color: rgba(255,255,255,0.85) !important;
        }

        /* Toggler */
        .navbar-toggler {
            border-color: rgba(99, 102, 241, 0.5);
        }

        .navbar-toggler-icon {
            filter: invert(1);
        }

        /* Nav links */
        .navbar-nav .nav-link {
            position: relative;
            padding: 0.45rem 0.85rem;
            border-radius: 8px;
            border: 1px solid transparent;
            color: rgba(255,255,255,0.85) !important;
            transition: all 0.2s ease;
            font-size: 0.9rem;
        }

        .navbar-nav .nav-link:hover {
            background: rgba(255, 255, 255, 0.12);
            border-color: rgba(99, 102, 241, 0.5);
            color: #fff !important;
            backdrop-filter: blur(6px);
        }

        .navbar-nav .nav-link.active {
            background: rgba(255, 255, 255, 0.18);
            border-color: rgba(99, 102, 241, 0.85);
            color: #fff !important;
            backdrop-filter: blur(10px);
            box-shadow: 0 0 0 1px rgba(99, 102, 241, 0.3),
                        inset 0 1px 0 rgba(255,255,255,0.15);
            font-weight: 500;
        }

        /* Dropdown */
        .dropdown-menu {
            background: rgba(30, 27, 75, 0.92) !important;
            backdrop-filter: blur(16px);
            border: 1px solid rgba(99, 102, 241, 0.4) !important;
            border-radius: 10px;
            padding: 0.4rem;
        }

        .dropdown-item {
            color: rgba(255,255,255,0.82) !important;
            border-radius: 6px;
            padding: 0.45rem 0.85rem;
            font-size: 0.875rem;
            border: 1px solid transparent;
            transition: all 0.15s ease;
        }

        .dropdown-item:hover {
            background: rgba(99, 102, 241, 0.25) !important;
            border-color: rgba(99, 102, 241, 0.5);
            color: #fff !important;
        }

        .dropdown-item.active,
        .dropdown-item:active {
            background: rgba(99, 102, 241, 0.4) !important;
            border-color: rgba(99, 102, 241, 0.7);
            color: #fff !important;
        }

        /* User dropdown right side */
        .navbar .ms-auto .nav-link {
            color: rgba(255,255,255,0.85) !important;
        }
    </style>

    {{-- ✅ Add this line — renders @push('styles') from child views --}}
    @stack('styles')
</head>

<body>
    @include('layouts.navbar')

    <div class="container">
        @yield('content')
    </div>

    {{-- All JS at bottom in correct order --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @stack('scripts')
</body>

</html>
