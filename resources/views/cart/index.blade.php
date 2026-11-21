@extends('layouts.app')

@section('title', 'Keranjang')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/catalog.css') }}">
@endpush

@section('content')
    <main class="catalog-page cart-page">
        <header class="catalog-heading">
            <span class="catalog-eyebrow">KOPISTHETICS / CART</span>
            <h1 class="catalog-title">Keranjang</h1>
            <p class="catalog-intro">Periksa item dan opsi pilihanmu.</p>
        </header>
        @if (session('status'))
            <p class="cart-order-alert" role="status">{{ session('status') }}</p>
        @endif
        @error('cart')
            <p class="catalog-error" role="alert">{{ $message }}</p>
        @enderror

        @if (count($items) === 0)
            <p class="cart-empty">Keranjangmu masih kosong.</p>
            <a class="catalog-primary-button cart-continue" href="{{ route('menu.index') }}">Jelajahi menu</a>
        @else
            <div class="cart-items">
                @foreach ($items as $key => $item)
                    <article class="cart-item">
                        @php
                            $cartItemImage = data_get($item, 'image_url', null);

                            if ($cartItemImage === null && ! empty($item['image_path'])) {
                                $cartItemImage = \App\Models\Product::make(['image_path' => $item['image_path']])->image_url;
                            }
                        @endphp

                        @if ($cartItemImage)
                            <img src="{{ $cartItemImage }}" alt="{{ $item['name'] }}" onerror="this.onerror=null;this.src='{{ asset('assets/img/logo/coffee-cup.png') }}';">
                        @endif
                        <div class="cart-item-details">
                            <h2>{{ $item['name'] }}</h2>
                            <p>{{ $item['customization_summary'] }}</p>
                            <p>{{ match ($item['order_type'] ?? null) {
                                'instant_pickup' => 'Pick-up',
                                'instant_delivery' => 'Instant delivery',
                                'expedited_shipping' => 'Pengiriman ekspedisi reguler',
                                default => '',
                            } }}</p>
                            <p>Rp {{ number_format((float) $item['price'], 0, ',', '.') }}</p>
                        </div>
                        <form method="POST" action="{{ route('cart.items.update', $key) }}">
                            @csrf
                            @method('PATCH')
                            <label for="quantity-{{ $key }}">Jumlah</label>
                            <div class="quantity-picker" data-quantity-picker>
                                <button class="quantity-picker-step" type="button" data-quantity-step="-1" aria-label="Kurangi jumlah" @disabled($item['quantity'] <= 1)>-</button>
                                <input class="quantity-picker-input" id="quantity-{{ $key }}" name="quantity" type="number" min="1" max="99" value="{{ $item['quantity'] }}" required>
                                <button class="quantity-picker-step" type="button" data-quantity-step="1" aria-label="Tambah jumlah" @disabled($item['quantity'] >= 99)>+</button>
                            </div>
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
