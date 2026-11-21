<main class="catalog-page">
    <header class="catalog-heading">
        <span class="catalog-eyebrow">KOPISTHETICS / {{ strtoupper($kind) }}</span>
        <h1 class="catalog-title">{{ $heading }}</h1>
        <p class="catalog-intro">{{ $description }}</p>
    </header>

    <section class="catalog-controls" aria-label="Sortir dan filter katalog">
        <details class="catalog-filter">
            <summary>
                <span class="catalog-filter-icon" aria-hidden="true">☷</span>
                <span>Filter</span>
                <span class="catalog-filter-caret" aria-hidden="true">+</span>
            </summary>
            <div class="catalog-filter-panel">
                <label for="catalog-category">Kategori</label>
                <div class="custom-select">
                    <select id="catalog-category" data-category-filter>
                        <option value="">Semua kategori</option>
                    </select>
                    <x-select-chevron />
                </div>
                <div class="catalog-facet-fields" data-facet-fields></div>
                <button type="button" data-clear-filters>Hapus filter</button>
            </div>
        </details>

        <label class="catalog-sort">
            <span aria-hidden="true">↕</span>
            <span>Sortir</span>
            <div class="custom-select">
                <select data-sort-filter aria-label="Urutkan katalog">
                    <option value="featured">Unggulan</option>
                    <option value="popular">Terpopuler</option>
                    <option value="newest">Terbaru</option>
                    <option value="price_high_low">Harga: tertinggi</option>
                    <option value="price_low_high">Harga: terendah</option>
                </select>
                <x-select-chevron />
            </div>
        </label>
    </section>

    <p class="catalog-status" data-catalog-status role="status" aria-live="polite">Memuat katalog...</p>
    <div
        class="catalog-grid"
        data-catalog
        data-catalog-kind="{{ $kind }}"
        data-products-endpoint="{{ $endpoint }}"
        data-filters-endpoint="{{ $filtersEndpoint }}"
        data-detail-base="{{ $detailBase }}"
        data-page-size="12"
    ></div>
    <button class="catalog-load-more" type="button" data-load-more hidden>Load More</button>
    <div class="catalog-faq">
        @include('catalog.partials.faq', ['kind' => $kind])
    </div>
</main>
