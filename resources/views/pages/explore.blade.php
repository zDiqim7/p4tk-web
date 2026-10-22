@extends('layouts.app')

@section('title', 'Jelajahi')

@section('content')
    <main>
        <nav aria-label="Navigasi halaman">
            <a href="{{ route('home') }}">Beranda</a>
            <a href="{{ route('menu.index') }}">Menu</a>
            <a href="{{ route('products.index') }}">Produk</a>
            <a href="{{ route('explore') }}">Jelajahi</a>
        </nav>
        <h1>Jelajahi DoomCoffee</h1>
        <p>Halaman tentang sejarah dan cerita DoomCoffee akan dikembangkan pada tahap desain berikutnya.</p>
    </main>
@endsection
