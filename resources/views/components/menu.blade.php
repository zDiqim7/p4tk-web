<section id="menu" class="menu-section">
  <div class="menu-section-inner">
    <div class="showcase-panel slide-in slide-in-left" aria-labelledby="menu-heading">
      <div class="showcase-heading">
        <p class="showcase-eyebrow">Our Favorite</p>
        <h2 id="menu-heading">Temukan <span>Kopi Pilihan Anda</span></h2>
        <p class="showcase-description">Menu favorit yang dibuat dengan bahan pilihan dan disajikan sepenuh hati.</p>
      </div>

      <div class="showcase-grid">
        @forelse ($menuItems as $item)
          <article class="showcase-card">
            <div class="showcase-card-image">
              <img src="{{ $item->image_url }}" alt="{{ $item->name }}" loading="lazy" onerror="this.onerror=null;this.src='{{ asset('assets/img/logo/coffee-cup.png') }}';">
            </div>
            <div class="showcase-card-content">
              <h3>{{ $item->name }}</h3>
              <p>{{ $item->description }}</p>
              <div class="showcase-card-footer">
                <span>Rp {{ number_format((float) $item->price, 0, ',', '.') }}</span>
                <a href="{{ route('menu.show', $item->slug) }}">Pesan</a>
              </div>
            </div>
          </article>
        @empty
          <p>Menu belum tersedia.</p>
        @endforelse
      </div>
      <div class="showcase-action">
        <a href="{{ route('menu.index') }}" class="showcase-all-link">Lihat Semua Menu</a>
      </div>
    </div>

    <!-- Bagian preview produk best seller -->
    <div class="showcase-panel slide-in slide-in-right" aria-labelledby="products-heading">
      <div class="showcase-heading">
        <p class="showcase-eyebrow">Katalog Produk</p>
        <h2 id="products-heading">Kopi dan <span>Perlengkapan</span></h2>
        <p class="showcase-description">Lihat biji kopi dan produk lain yang tersedia di katalog.</p>
      </div>

      <div class="showcase-grid">
        @forelse ($products as $product)
          <article class="showcase-card">
            <div class="showcase-card-image">
              <img src="{{ $product->image_url }}" alt="{{ $product->name }}" loading="lazy" onerror="this.onerror=null;this.src='{{ asset('assets/img/logo/coffee-cup.png') }}';">
            </div>
            <div class="showcase-card-content">
              <h3>{{ $product->name }}</h3>
              <p>{{ $product->description }}</p>
              <div class="showcase-card-footer">
                <span>Rp {{ number_format((float) $product->price, 0, ',', '.') }}</span>
                <a href="{{ route('products.show', $product->slug) }}">Detail</a>
              </div>
            </div>
          </article>
        @empty
          <p>Produk belum tersedia.</p>
        @endforelse
      </div>
      <div class="showcase-action">
        <a href="{{ route('products.index') }}" class="showcase-all-link">Lihat Semua Produk</a>
      </div>
    </div>
  </div>
</section>
