<section id="menu" class="menu-section">
  <div class="menu-section-inner">
    <div class="showcase-panel slide-in slide-in-left" aria-labelledby="menu-heading">
      <div class="showcase-heading">
        <p class="showcase-eyebrow">Our Favorite</p>
        <h2 id="menu-heading">Temukan <span>Kopi Pilihan Anda</span></h2>
        <p class="showcase-description">Menu favorit yang dibuat dengan bahan pilihan dan disajikan sepenuh hati.</p>
      </div>

      @php
        $menuItems = [
          ['name' => 'Bagel', 'description' => 'Bagel panggang renyah untuk teman kopi.', 'price' => 'Rp 25.000', 'image' => 'bagel.jpg'],
          ['name' => 'Croissant', 'description' => 'Pastry berlapis yang lembut dan buttery.', 'price' => 'Rp 28.000', 'image' => 'croissant.jpg'],
          ['name' => 'Cappuccino', 'description' => 'Espresso seimbang dengan foam susu lembut.', 'price' => 'Rp 30.000', 'image' => 'cappuccino.png'],
          ['name' => 'Cold Brew', 'description' => 'Kopi seduh dingin yang halus dan menyegarkan.', 'price' => 'Rp 32.000', 'image' => 'cold-brew.jpg'],
        ];
      @endphp
      <div class="showcase-grid">
        @foreach ($menuItems as $item)
          <article class="showcase-card">
            <div class="showcase-card-image">
              <img src="{{ asset('assets/img/menu/' . $item['image']) }}" alt="{{ $item['name'] }}" loading="lazy">
            </div>
            <div class="showcase-card-content">
              <h3>{{ $item['name'] }}</h3>
              <p>{{ $item['description'] }}</p>
              <div class="showcase-card-footer">
                <span>{{ $item['price'] }}</span>
                <a href="#kontak">Pesan</a>
              </div>
            </div>
          </article>
        @endforeach
      </div>
      <div class="showcase-action">
        <a href="{{ route('menu.index') }}" class="showcase-all-link">Lihat Semua Menu</a>
      </div>
    </div>

    <!-- Bagian preview produk best seller -->
    <div class="showcase-panel slide-in slide-in-right" aria-labelledby="products-heading">
      <div class="showcase-heading">
        <p class="showcase-eyebrow">Best Sellers</p>
        <h2 id="products-heading">Produk <span>Paling Disukai</span></h2>
        <p class="showcase-description">Pilihan best seller untuk menemani waktu santai Anda.</p>
      </div>

      @php
        $products = [
          ['name' => 'Matcha Latte', 'description' => 'Matcha pilihan dengan susu yang creamy.', 'price' => 'Rp 32.000', 'image' => 'matcha-latte.png'],
          ['name' => 'Americano', 'description' => 'Espresso dengan rasa kopi yang bersih dan bold.', 'price' => 'Rp 24.000', 'image' => 'americano.png'],
          ['name' => 'Strawberry Cake', 'description' => 'Kue lembut dengan rasa stroberi yang segar.', 'price' => 'Rp 38.000', 'image' => 'a-strawberry-cake.jpg'],
          ['name' => 'Chicken Burger', 'description' => 'Burger ayam hangat dengan sayuran segar.', 'price' => 'Rp 42.000', 'image' => 'chicken-burger.jpg'],
        ];
      @endphp
      <div class="showcase-grid">
        @foreach ($products as $product)
          <article class="showcase-card">
            <div class="showcase-card-image">
              <img src="{{ asset('assets/img/menu/' . $product['image']) }}" alt="{{ $product['name'] }}" loading="lazy">
            </div>
            <div class="showcase-card-content">
              <h3>{{ $product['name'] }}</h3>
              <p>{{ $product['description'] }}</p>
              <div class="showcase-card-footer">
                <span>{{ $product['price'] }}</span>
                <a href="#kontak">Pesan</a>
              </div>
            </div>
          </article>
        @endforeach
      </div>
      <div class="showcase-action">
        <a href="{{ route('products.index') }}" class="showcase-all-link">Lihat Semua Produk</a>
      </div>
    </div>
  </div>
</section>
