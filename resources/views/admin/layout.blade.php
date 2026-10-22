<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#fffdf7">
    <title>@yield('title', 'Admin') - Kopisthetics</title>
    <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}">
</head>
<body class="@yield('body-class', 'admin-page')">
    @auth
        <header class="admin-header">
            <a class="admin-brand" href="{{ route('admin.dashboard') }}">Kopisthetics <span>ADMIN</span></a>
            <nav aria-label="Navigasi admin">
                <a href="{{ route('admin.categories.index') }}">Kategori</a>
                <a href="{{ route('admin.products.index') }}">Produk</a>
            </nav>
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button class="admin-button admin-button-secondary" type="submit">Keluar</button>
            </form>
        </header>
    @endauth

    <div class="admin-content">
        @if (session('status'))
            <p class="admin-flash admin-flash-success" role="status">{{ session('status') }}</p>
        @endif
        @if (session('error'))
            <p class="admin-flash admin-flash-error" role="alert">{{ session('error') }}</p>
        @endif
        @yield('content')
    </div>
</body>
</html>
