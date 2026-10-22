<!-- Hero Section-->
  <section id="home" class="mx-auto grid min-h-[calc(100vh-80px)] max-w-6xl items-center gap-12 px-6 py-16 sm:px-8 md:grid-cols-2 lg:gap-16">
    <!-- Konten Kiri -->
    <div class="fade-up max-w-xl">
      <p class="mb-5 inline-block border-2 border-black bg-[var(--card)] px-3 py-2 text-sm font-bold uppercase shadow-[3px_3px_0_0_#000]">
        Satu langkah masuk, satu cangkir kopi closer ke mood yang lebih baik. Selamat datang!
      </p>
      <h1 class="text-4xl font-black leading-tight sm:text-5xl lg:text-6xl">
        Rasakan <span class="text-[var(--primary)]">Bedanya</span>
      </h1>
      <!-- Animasi di bagian sini, typewriter -->
      <p class="mt-6 max-w-lg leading-7">
        <span id="typewriter-text" aria-hidden="true">Rasakan kelezatan di setiap tegukan, diolah dari biji kopi pilihan dan diseduh presisi oleh barista berpengalaman.</span>
        <span id="typewriter-announcement" class="sr-only" aria-live="polite" aria-atomic="true">Rasakan kelezatan di setiap tegukan, diolah dari biji kopi pilihan dan diseduh presisi oleh barista berpengalaman.</span>
      </p>

      <div class="mt-8 flex flex-wrap gap-4">
        <a href="#menu" class="border-2 border-black bg-[var(--accent-yellow)] px-5 py-3 font-bold shadow-[4px_4px_0_0_#000] transition-transform hover:translate-x-0.5 hover:translate-y-0.5 hover:shadow-[3px_3px_0_0_#000]">
          Jelajahi
        </a>
        <a href="#menu" class="border-2 border-black bg-[var(--primary)] px-5 py-3 font-bold text-[var(--primary-foreground)] shadow-[4px_4px_0_0_#000] transition-transform hover:translate-x-0.5 hover:translate-y-0.5 hover:shadow-[3px_3px_0_0_#000]">
          Cerita Kami
        </a>
      </div>
    </div>

    <!-- Konten Kanan -->
    <div class="fade-up relative mx-auto w-full max-w-xl">
      <div class="absolute inset-3 translate-x-3 translate-y-3 border-2 border-black bg-[var(--primary)]"></div>
      <img
        src="{{ asset('assets/img/main-1.jpg') }}"
        alt="Cangkir kopi di atas biji kopi"
        class="relative aspect-[4/3] w-full border-2 border-black object-cover shadow-[5px_5px_0_0_#000]"
      >
    </div>
  </section>
  <!-- Hero Close -->
