<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ASM</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    @include('layouts.navbar')

    <div class="container">
        @yield('content')
    </div>

    {{-- ✅ Required: allows @push('scripts') to work from any view --}}
    @stack('scripts')
</body>

</html>
