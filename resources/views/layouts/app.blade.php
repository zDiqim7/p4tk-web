<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#D4E7FE">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Kopisthetics')</title>
    <script>
        document.documentElement.dataset.theme = localStorage.getItem('kopisthetics-theme') || 'light';
    </script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('assets/css/global.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/cart-drawer.css') }}">
    @stack('styles')
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" type="text/css">
    <link rel="icon" type="image/png" href="{{ asset('assets/img/madoka-suka-panci-gratis.jpg') }}">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-[var(--background)] text-[var(--foreground)] antialiased">
    @include('components.navigation')
    @yield('content')
    @include('components.footer')
    @include('components.cart-drawer')

    <script src="{{ asset('assets/js/typewriter.js') }}"></script>
    <script src="{{ asset('assets/js/scroll-progress.js') }}"></script>
    <script src="{{ asset('assets/js/render.js') }}"></script>
    <script src="{{ asset('assets/js/ui.js') }}" defer></script>
    <script src="{{ asset('assets/js/cart.js') }}" defer></script>
    <script src="{{ asset('assets/js/scroll-reveal.js') }}" defer></script>
    @stack('scripts')
    <script>
        if (window.lucide) {
            window.lucide.createIcons();
        }
    </script>
</body>
</html>
