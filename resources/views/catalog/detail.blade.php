@extends('layouts.app')

@section('title', $product->name)

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/catalog.css') }}">
@endpush

@section('content')
    <main class="catalog-page product-detail-page">
        <article class="product-detail">
            <div class="product-detail-image">
                @if ($product->image_path)
                    <img src="{{ asset($product->image_path) }}" alt="{{ $product->name }}">
                @else
                    <div class="product-image-placeholder" aria-hidden="true">K</div>
                @endif
            </div>
            <div class="product-detail-content">
                <span class="catalog-eyebrow">{{ $product->category->name }}</span>
                <h1>{{ $product->name }}</h1>
                <p class="product-detail-price">Rp {{ number_format((float) $product->price, 0, ',', '.') }}</p>
                @if ($product->description)
                    <p class="product-detail-description">{{ $product->description }}</p>
                @endif

                <form class="product-detail-form" method="POST" action="{{ route('cart.items.store') }}">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <div class="option-group">
                        <label for="quantity">Jumlah</label>
                        <input id="quantity" name="quantity" type="number" min="1" max="99" value="1" required>
                    </div>

                    @if ($product->catalog_type === 'product')
                        <div class="option-group">
                            <label for="size">Ukuran</label>
                            <select id="size" name="customization[size]" required>
                                <option value="small">Small</option>
                                <option value="medium" selected>Medium</option>
                                <option value="big">Big</option>
                            </select>
                        </div>
                        <div class="option-group">
                            <label for="variant">Varian</label>
                            <select id="variant" name="customization[variant]" required>
                                <option value="whole_bean">Whole Bean</option>
                                <option value="ground">Ground</option>
                                <option value="espresso">Espresso</option>
                            </select>
                        </div>
                    @else
                        <div class="option-group">
                            <label for="server-option">Pilihan penyajian</label>
                            <select id="server-option" name="customization[server_option]" required>
                                <option value="hot">Hot</option>
                                <option value="cold">Cold</option>
                            </select>
                        </div>
                        <fieldset class="option-group">
                            <legend>Add-ons</legend>
                            <label><input type="checkbox" name="customization[add_ons][]" value="extra_shot"> Extra shot (+Rp 8.000)</label>
                            <label><input type="checkbox" name="customization[add_ons][]" value="oat_milk"> Oat milk (+Rp 7.000)</label>
                            <label><input type="checkbox" name="customization[add_ons][]" value="extra_topping"> Extra topping (+Rp 5.000)</label>
                        </fieldset>
                    @endif

                    @error('customization')
                        <p class="catalog-error" role="alert">{{ $message }}</p>
                    @enderror
                    <button class="catalog-primary-button" type="submit">Tambahkan ke Keranjang</button>
                </form>
            </div>
        </article>
    </main>
@endsection
