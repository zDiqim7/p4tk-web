@extends('layouts.app')

@section('title', $product->name)

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/catalog.css') }}">
@endpush

@section('content')
    <main class="catalog-page product-detail-page">
        <article class="product-detail">
            <div class="product-detail-image-wrap">
                <div class="product-detail-image">
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="product-detail-photo" onerror="this.onerror=null;this.src='{{ asset('assets/img/logo/coffee-cup.png') }}';">
                </div>
            </div>

            <div class="product-detail-content">
                <span class="catalog-eyebrow">{{ $product->category->name }}</span>
                <h1>{{ $product->name }}</h1>
                <div class="product-detail-price-row">
                    <p class="product-detail-price">Rp {{ number_format((float) $product->price, 0, ',', '.') }}</p>
                    <span class="product-detail-tag">Fresh roasted</span>
                </div>
                @if ($product->description)
                    <p class="product-detail-description">{{ $product->description }}</p>
                @endif

                <form class="product-detail-form" method="POST" action="{{ route('cart.items.store') }}" data-cart-add-form>
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">

                    <div class="option-group">
                        <label for="quantity">Jumlah</label>
                        <div class="quantity-picker" data-quantity-picker>
                            <button class="quantity-picker-step" type="button" data-quantity-step="-1" aria-label="Kurangi jumlah" disabled>-</button>
                            <input class="quantity-picker-input" id="quantity" name="quantity" type="number" min="1" max="99" value="{{ old('quantity', 1) }}" required>
                            <button class="quantity-picker-step" type="button" data-quantity-step="1" aria-label="Tambah jumlah">+</button>
                        </div>
                    </div>

                    @if ($product->catalog_type === 'product')
                        <fieldset class="option-group option-choice-group">
                            <legend>Grind size</legend>
                            <div class="option-choice-list">
                                @foreach ([
                                    'whole_bean' => 'Whole bean',
                                    'coarse' => 'Coarse',
                                    'fine' => 'Fine',
                                ] as $value => $label)
                                    <label class="option-choice-card">
                                        <input
                                            class="option-choice-input"
                                            type="radio"
                                            name="customization[grind_size]"
                                            value="{{ $value }}"
                                            @checked(old('customization.grind_size', 'whole_bean') === $value)
                                            required
                                        >
                                        <span>{{ $label }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </fieldset>

                        <fieldset class="option-group option-choice-group">
                            <legend>Pack size</legend>
                            <div class="option-choice-list">
                                @foreach ([
                                    '250g' => '250 g',
                                    '1kg' => '1 kg',
                                ] as $value => $label)
                                    <label class="option-choice-card">
                                        <input
                                            class="option-choice-input"
                                            type="radio"
                                            name="customization[pack_size]"
                                            value="{{ $value }}"
                                            @checked(old('customization.pack_size', '250g') === $value)
                                            required
                                        >
                                        <span>{{ $label }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </fieldset>

                        <input type="hidden" name="order_type" value="expedited_shipping">

                        @php
                            $roast = $product->catalog_attributes['roast_level'] ?? 'Medium';
                            $roastValue = match ($roast) {
                                'Light' => 25,
                                'Medium' => 50,
                                'Dark' => 85,
                                default => 50,
                            };
                            $flavors = array_filter([
                                $product->catalog_attributes['coffee_tastes_like'] ?? 'Citrus',
                                'Cherry',
                                'Caramel',
                            ], fn ($item) => ! empty($item));
                            $origin = $product->catalog_attributes['origin'] ?? 'Indonesia';
                            $process = $product->catalog_attributes['process'] ?? 'Washed';
                            $elevation = $product->catalog_attributes['elevation'] ?? '1200-1600m';
                            $subRegion = $product->catalog_attributes['sub_region'] ?? 'Toraja';
                            $region = $product->catalog_attributes['region'] ?? 'Sulawesi';
                        @endphp

                        <section class="product-specs-block" aria-label="Metadata produk kopi">
                            <div class="spec-section-header">
                                <h2>Roast level</h2>
                            </div>
                            <div class="roast-meter" aria-label="Tingkat sangrai {{ $roast }}">
                                <div class="roast-meter-labels">
                                    <span>Light</span>
                                    <span>{{ $roast }}</span>
                                    <span>Dark</span>
                                </div>
                                <div class="roast-meter-track">
                                    <div class="roast-meter-fill" style="width: {{ $roastValue }}%"></div>
                                </div>
                            </div>

                            <div class="spec-section-header">
                                <h2>Flavor profile</h2>
                            </div>
                            <div class="flavor-grid">
                                @foreach ($flavors as $flavor)
                                    <div class="flavor-item">
                                        <span class="flavor-badge">✓</span>
                                        <span>{{ $flavor }}</span>
                                    </div>
                                @endforeach
                            </div>

                            <div class="spec-section-header">
                                <h2>Origin & process</h2>
                            </div>
                            <div class="origin-grid">
                                <div class="origin-item">
                                    <span class="origin-label">Country of Origin</span>
                                    <strong>{{ $origin }}</strong>
                                </div>
                                <div class="origin-item">
                                    <span class="origin-label">Process</span>
                                    <strong>{{ $process }}</strong>
                                </div>
                                <div class="origin-item">
                                    <span class="origin-label">Elevation</span>
                                    <strong>{{ $elevation }}</strong>
                                </div>
                                <div class="origin-item">
                                    <span class="origin-label">Sub Region</span>
                                    <strong>{{ $subRegion }}</strong>
                                </div>
                            </div>
                        </section>
                    @else
                        <fieldset class="option-group option-choice-group">
                            <legend>Temperature</legend>
                            <div class="option-choice-list">
                                @foreach ([
                                    'hot' => 'Hot',
                                    'cold' => 'Cold',
                                ] as $value => $label)
                                    <label class="option-choice-card">
                                        <input
                                            class="option-choice-input"
                                            type="radio"
                                            name="customization[temperature]"
                                            value="{{ $value }}"
                                            @checked(old('customization.temperature', 'hot') === $value)
                                            required
                                        >
                                        <span>{{ $label }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </fieldset>

                        <div class="option-group">
                            <label for="sugar-level">Sugar level</label>
                            <div class="custom-select">
                                <select id="sugar-level" name="customization[sugar_level]" required>
                                    <option value="none">No sugar</option>
                                    <option value="low">Low</option>
                                    <option value="regular" @selected(old('customization.sugar_level', 'regular') === 'regular')>Regular</option>
                                    <option value="high">High</option>
                                </select>
                                <x-select-chevron />
                            </div>
                        </div>

                        <fieldset class="option-group">
                            <legend>Add-ons</legend>
                            <label><input type="checkbox" name="customization[add_ons][]" value="extra_shot"> Extra shot (+Rp 8.000)</label>
                            <label><input type="checkbox" name="customization[add_ons][]" value="oat_milk"> Oat milk (+Rp 7.000)</label>
                            <label><input type="checkbox" name="customization[add_ons][]" value="extra_topping"> Extra topping (+Rp 5.000)</label>
                        </fieldset>

                        <div class="option-group">
                            <label for="order-type">Tipe pemenuhan</label>
                            <div class="custom-select">
                                <select id="order-type" name="order_type" required>
                                    <option value="instant_pickup" @selected(old('order_type', 'instant_pickup') === 'instant_pickup')>Pick-up</option>
                                    <option value="instant_delivery" @selected(old('order_type') === 'instant_delivery')>Instant delivery</option>
                                </select>
                                <x-select-chevron />
                            </div>
                        </div>
                    @endif

                    @error('customization')
                        <p class="catalog-error" role="alert">{{ $message }}</p>
                    @enderror
                    @error('order_type')
                        <p class="catalog-error" role="alert">{{ $message }}</p>
                    @enderror
                    @error('quantity')
                        <p class="catalog-error" role="alert">{{ $message }}</p>
                    @enderror
                    <p class="cart-feedback" data-cart-feedback role="status" aria-live="polite"></p>
                    <button class="catalog-primary-button" type="submit">Tambahkan ke Keranjang</button>
                </form>
            </div>
        </article>

        @php
            $similarItems = \App\Models\Product::query()
                ->where('catalog_type', $product->catalog_type)
                ->where('is_available', true)
                ->whereKeyNot($product->getKey())
                ->limit(4)
                ->get();
        @endphp

        @if ($similarItems->isNotEmpty())
            <section class="product-similar" aria-labelledby="similar-products-title">
                <div class="similar-header">
                    <p class="catalog-eyebrow">Produk serupa</p>
                    <h2 id="similar-products-title">Jelajahi pilihan lain</h2>
                </div>
                <div class="similar-grid">
                    @foreach ($similarItems as $similar)
                        <a href="{{ $product->catalog_type === 'product' ? route('products.show', $similar->slug) : route('menu.show', $similar->slug) }}" class="similar-card">
                            <div class="similar-card-media">
                                <img src="{{ $similar->image_url }}" alt="{{ $similar->name }}" onerror="this.onerror=null;this.src='{{ asset('assets/img/logo/coffee-cup.png') }}';">
                            </div>
                            <div class="similar-card-content">
                                <span>{{ $similar->category->name ?? 'Katalog' }}</span>
                                <h3>{{ $similar->name }}</h3>
                                <strong>Rp {{ number_format((float) $similar->price, 0, ',', '.') }}</strong>
                            </div>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif

        @php
            $reviews = data_get($product, 'reviews', data_get($product, 'catalog_attributes.reviews', []));
            $reviews = is_array($reviews) ? $reviews : (is_iterable($reviews) ? iterator_to_array($reviews) : []);
            $reviewRatings = array_values(array_filter(array_map(static fn ($review) => isset($review['rating']) ? (int) $review['rating'] : null, $reviews)));
            $reviewCount = count($reviewRatings);
            $averageReview = $reviewCount > 0 ? round(array_sum($reviewRatings) / $reviewCount, 1) : 0;
            $visibleReviews = array_slice($reviews, 0, 3);
        @endphp

        <section class="product-reviews" aria-labelledby="product-reviews-title">
            <div class="similar-header">
                <p class="catalog-eyebrow">Ulasan</p>
                <h2 id="product-reviews-title">Ulasan & Rating Pelanggan</h2>
            </div>

            @if ($reviewCount > 0)
                <div class="review-summary">
                    <div class="review-score-wrap">
                        <span class="review-score">{{ number_format($averageReview, 1) }} / 5.0</span>
                        <span class="review-stars" aria-label="Rating {{ $averageReview }} dari 5">{{ str_repeat('★', (int) round($averageReview)) }}</span>
                    </div>
                    <p>{{ $reviewCount }} ulasan dari pelanggan.</p>
                </div>

                <div class="review-list">
                    @foreach ($visibleReviews as $review)
                        @php
                            $reviewRating = isset($review['rating']) ? max(1, min(5, (int) $review['rating'])) : 5;
                            $reviewAuthor = $review['author'] ?? $review['customer'] ?? 'Customer';
                            $reviewMessage = $review['comment'] ?? $review['message'] ?? '';
                        @endphp

                        <article class="review-item">
                            <div class="review-item-header">
                                <strong>{{ $reviewAuthor }}</strong>
                                <span aria-label="Rating {{ $reviewRating }} dari 5">{{ str_repeat('★', $reviewRating) }}</span>
                            </div>
                            @if ($reviewMessage !== '')
                                <p>{{ $reviewMessage }}</p>
                            @endif
                        </article>
                    @endforeach
                </div>
            @else
                <div class="review-empty-state" role="status">
                    <p>Belum Ada Rating untuk Produk Ini</p>
                    <span>Ulasan pelanggan akan tampil di sini setelah tersedia.</span>
                </div>
            @endif
        </section>
    </main>
@endsection
