<header class="site-header">
  <nav class="site-nav" aria-label="Navigasi utama">
    <a href="{{ route('home') }}" class="site-brand" aria-label="Kopisthetics, beranda">
      <!-- <img src="{{ asset('assets/img/logo/cup.png') }}" alt="" class="site-brand-logo"> -->
      <span class="inline-block bg-black text-white px-3 py-1 font-black text-xl border-2 border-black tracking-wider uppercase shadow-[4px_4px_0px_0px_#2563EB]">KOPISTHETICS</span>
    </a>

    <div class="site-nav-links" id="site-nav-links">
      <a href="{{ route('home') }}" class="site-nav-link">Beranda</a>
      <a href="{{ route('explore') }}" class="site-nav-link">Jelajahi</a>
      <a href="{{ route('menu.index') }}" class="site-nav-link">Menu</a>
      <a href="{{ route('products.index') }}" class="site-nav-link">Produk</a>
      <a href="{{ route('about') }}" class="site-nav-link">Tentang Kami</a>
    </div>

    <div class="site-nav-actions">
      <button class="cart-drawer-trigger" type="button" data-cart-open aria-haspopup="dialog" aria-controls="cart-drawer">
        <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M3 3h2l2.4 12.2a2 2 0 0 0 2 1.6h8.8a2 2 0 0 0 2-1.6L22 8H6"></path>
          <circle cx="10" cy="21" r="1"></circle>
          <circle cx="19" cy="21" r="1"></circle>
        </svg>
        <span>Keranjang</span>
        <span class="cart-count-badge" data-cart-count hidden>0</span>
      </button>
      <button class="theme-toggle" type="button" aria-label="Ganti ke tema gelap" title="Ganti tema">
        <svg class="theme-icon theme-icon-moon" aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M20.9 13A9 9 0 0 1 11 3.1 9 9 0 1 0 20.9 13Z"></path>
        </svg>
        <svg class="theme-icon theme-icon-sun" aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="12" cy="12" r="4"></circle>
          <path d="M12 2v2m0 16v2M4.93 4.93l1.42 1.42m11.3 11.3 1.42 1.42M2 12h2m16 0h2M4.93 19.07l1.42-1.42m11.3-11.3 1.42-1.42"></path>
        </svg>
        <span class="theme-toggle-label">Tema</span>
      </button>
      <button class="nav-menu-toggle" type="button" aria-label="Buka menu navigasi" aria-controls="site-nav-links" aria-expanded="false">
        <svg class="menu-icon menu-icon-open" aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
          <path d="M4 6h16M4 12h16M4 18h16"></path>
        </svg>
        <svg class="menu-icon menu-icon-close" aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
          <path d="m6 6 12 12M18 6 6 18"></path>
        </svg>
      </button>
    </div>
  </nav>
  <div class="scroll-progress" role="progressbar" aria-label="Progress halaman" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
    <span class="scroll-progress-bar"></span>
  </div>
</header>
