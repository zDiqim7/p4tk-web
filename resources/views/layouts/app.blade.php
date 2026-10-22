<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#D4E7FE">
    <title>@yield('title', 'DoomCoffee')</title>
    <script>
        document.documentElement.dataset.theme = localStorage.getItem('kopisthetics-theme') || 'light';
    </script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('assets/css/global.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
    @stack('styles')
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" type="text/css">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-[var(--background-bone)] text-[var(--foreground)] antialiased">
    @yield('content')

    <script src="{{ asset('assets/js/typewriter.js') }}"></script>
    <script src="{{ asset('assets/js/scroll-progress.js') }}"></script>
    <script src="{{ asset('assets/js/render.js') }}"></script>
    <script src="{{ asset('assets/js/ui.js') }}" defer></script>
    <script>
        if (window.lucide) {
            window.lucide.createIcons();
        }
    </script>
</body>
</html>
