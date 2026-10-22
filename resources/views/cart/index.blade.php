@extends('layouts.app')

@section('title', 'Keranjang')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/catalog.css') }}">
@endpush

@section('content')
    <main class="catalog-page cart-page">
        <nav class="catalog-nav" aria-label="Navigasi halaman">
            <a href="{{ route('home') }}">Beranda</a>
            <a href="{{ route('menu.index') }}">Menu</a>
            <a href="{{ route('products.index') }}">Produk</a>
        </nav>
        <header class="catalog-heading">
            <span class="catalog-eyebrow">KOPISTHETICS / CART</span>
            <h1 class="catalog-title">Keranjang</h1>
            <p class="catalog-intro">Periksa item dan opsi pilihanmu.</p>
        </header>

        @if (count($items) === 0)
            <p class="cart-empty">Keranjangmu masih kosong.</p>
            <a class="catalog-primary-button cart-continue" href="{{ route('menu.index') }}">Jelajahi menu</a>
        @else
            <div class="cart-items">
                @foreach ($items as $key => $item)
                    <article class="cart-item">
                        @if ($item['image_path'])
                            <img src="{{ asset($item['image_path']) }}" alt="{{ $item['name'] }}">
                        @endif
                        <div class="cart-item-details">
                            <h2>{{ $item['name'] }}</h2>
                            <p>{{ $item['customization_summary'] }}</p>
                            <p>Rp {{ number_format((float) $item['price'], 0, ',', '.') }}</p>
                        </div>
                        <form method="POST" action="{{ route('cart.items.update', $key) }}">
                            @csrf
                            @method('PATCH')
                            <label for="quantity-{{ $key }}">Jumlah</label>
                            <input id="quantity-{{ $key }}" name="quantity" type="number" min="1" max="99" value="{{ $item['quantity'] }}" required>
                            <button type="submit">Perbarui</button>
                        </form>
                        <form method="POST" action="{{ route('cart.items.destroy', $key) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Hapus</button>
                        </form>
                    </article>
                @endforeach
            </div>
            <p class="cart-total">Subtotal: Rp {{ number_format((float) $subtotal, 0, ',', '.') }}</p>
        @endif
    </main>
@endsection
