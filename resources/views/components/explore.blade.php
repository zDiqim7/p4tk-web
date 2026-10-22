<!-- Section Jelajahi -->
  <section id="jelajahi" class=" border-black bg-[var(--card)] mx-auto h-full max-w-6xl px-6 py-24 sm:px-8">
    <div class="ms-auto grid max-w-7xl items-center gap-8 md:grid-cols-2 ">
      <img 
       src="{{ asset('assets/img/jelajahi.jpg') }}" 
       alt="Jelajahi" 
       class="w-full h-full rounded-3xl object-cover shadow-[5px_5px_0_0_#000]">
      <div class="mt-6 justify-start p-2 justify-between gap-2">
        <p class="font-light tracking-light pb-2">Temukan Kesukaan Mu</p>
        <h2 class="uppercase text-3x1 font-black tracking-tight text-[var(--foreground)] text-light">
          Jelajahi berbagai pilihan kopi yang tersedia di toko kami.
        </h2>
        <p class="mt-3 max-w-2xl font-medium text-sm text-[var(--foreground)] pb-4">
          Kami percaya kopi enak berasal dari bahan berkualitas dan dedikasi tinggi. Di sini, setiap biji kopi terbaik diproses dan disajikan secara presisi oleh barista profesional untuk menemani momen harimu.
        </p>
        <a href="{{ route('explore') }}" class="border border-2 border-[var(--accent-yellow)] bg-[var(--accent-yellow)] pb-2 px-2 py-2 font-bold
        shadow-[5px_5px_0_0_#000] shadow text-sm text-black hover:shadow-[3px_3px_0_0_#000]">Jelajahi</a>
      </div>
    </div>

  </section>
  <!-- Jelajahi Close -->
