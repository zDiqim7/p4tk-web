@extends('layouts.app')

@section('title', 'Checkout')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/catalog.css') }}">
@endpush

@section('content')
    <main class="catalog-page checkout-page">
        <header class="catalog-heading">
            <span class="catalog-eyebrow">KOPISTHETICS / CHECKOUT</span>
            <h1 class="catalog-title">Checkout</h1>
            <p class="catalog-intro">Konfirmasi tipe pemenuhan dan detail penerima untuk membuat pesanan simulasi.</p>
        </header>

        @if ($items->isEmpty())
            <p class="cart-empty">Keranjangmu masih kosong.</p>
            <a class="catalog-primary-button cart-continue" href="{{ route('menu.index') }}">Jelajahi menu</a>
        @else
            <form class="checkout-form" method="POST" action="{{ route('checkout.store') }}">
                @csrf
                <div class="checkout-summary">
                    <h2>Ringkasan pesanan</h2>
                    @foreach ($items as $item)
                        <p>
                            {{ $item['name'] }} × {{ $item['quantity'] }} —
                            {{ $item['customization_summary'] }} —
                            {{ match ($item['order_type'] ?? null) {
                                'instant_pickup' => 'Pick-up',
                                'instant_delivery' => 'Instant delivery',
                                'expedited_shipping' => 'Pengiriman ekspedisi reguler',
                                default => '',
                            } }}
                        </p>
                    @endforeach
                    <p class="cart-total">Subtotal: Rp {{ number_format($subtotal, 0, ',', '.') }}</p>
                </div>

                <div class="option-group">
                    <label for="checkout-order-type">Tipe pemenuhan</label>
                    <div class="custom-select">
                        <select id="checkout-order-type" name="order_type" required>
                            @if ($orderType === 'instant_pickup')
                                <option value="instant_pickup" selected>Pick-up (ambil sendiri)</option>
                            @elseif ($orderType === 'instant_delivery')
                                <option value="instant_delivery" selected>Instant delivery</option>
                            @elseif ($orderType === 'expedited_shipping')
                                <option value="expedited_shipping" selected>Pengiriman ekspedisi reguler</option>
                            @endif
                        </select>
                        <x-select-chevron />
                    </div>
                    @error('order_type')
                        <p class="catalog-error" role="alert">{{ $message }}</p>
                    @enderror
                </div>

                <div class="option-group">
                    <label for="customer-name">Nama penerima</label>
                    <input id="customer-name" name="name" value="{{ old('name') }}" maxlength="255" required>
                    @error('name')
                        <p class="catalog-error" role="alert">{{ $message }}</p>
                    @enderror
                </div>

                <div class="option-group">
                    <label for="customer-email">Email (opsional)</label>
                    <input id="customer-email" name="email" type="email" value="{{ old('email') }}" maxlength="255">
                    @error('email')
                        <p class="catalog-error" role="alert">{{ $message }}</p>
                    @enderror
                </div>

                <div class="option-group">
                    <label for="customer-phone">Nomor telepon</label>
                    <input id="customer-phone" name="phone" type="tel" value="{{ old('phone') }}" maxlength="30" required>
                    @error('phone')
                        <p class="catalog-error" role="alert">{{ $message }}</p>
                    @enderror
                </div>

                @if ($orderType !== 'instant_pickup')
                    <div class="option-group">
                        <label for="delivery-address">Alamat penerima</label>
                        <textarea id="delivery-address" name="delivery_address" maxlength="1000" required>{{ old('delivery_address') }}</textarea>
                        @error('delivery_address')
                            <p class="catalog-error" role="alert">{{ $message }}</p>
                        @enderror
                    </div>
                @endif

                @error('cart')
                    <p class="catalog-error" role="alert">{{ $message }}</p>
                @enderror
                <p class="checkout-notice">Checkout ini simulasi internal. Pembayaran online belum diaktifkan.</p>
                <button class="catalog-primary-button" type="submit">Buat pesanan</button>
            </form>
        @endif
    </main>
@endsection
