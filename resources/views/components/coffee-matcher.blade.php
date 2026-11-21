<section class="coffee-match-promo border-y-2 border-black bg-[var(--primary-foreground)] px-6 py-16 sm:px-8">
    <div class="mx-auto grid max-w-6xl items-center gap-10 lg:grid-cols-[minmax(0,0.9fr)_minmax(0,1.1fr)]">
        <div>
            <p class="text-sm font-bold uppercase tracking-[0.3em] text-[var(--primary)]">Bukan tebak-tebakan</p>
            <h2 class="mt-3 text-3xl font-black uppercase tracking-tight sm:text-4xl">
                Kenali rasa yang paling cocok buatmu.
            </h2>
            <p class="mt-4 max-w-xl text-sm font-medium leading-7 text-[var(--muted-foreground)] sm:text-base">
                Pilih karakter rasa yang kamu suka. Kami cocokkan dengan profil kopi dan menu yang datanya sudah tercatat.
            </p>
            <a
                href="{{ route('explore') }}"
                class="mt-7 inline-flex border-2 border-black bg-[var(--accent-yellow)] px-5 py-3 font-bold text-[var(--accent-yellow-foreground)] shadow-[4px_4px_0_0_#000] transition-transform hover:translate-x-0.5 hover:translate-y-0.5 hover:shadow-[3px_3px_0_0_#000]"
            >
                Coba Coffee Matcher
            </a>
        </div>

        <div class="coffee-match-promo-card">
            <div class="coffee-match-promo-gauge" aria-hidden="true">
                <span class="coffee-match-promo-ring coffee-match-promo-ring-outer"></span>
                <span class="coffee-match-promo-ring coffee-match-promo-ring-middle"></span>
                <img
                    class="coffee-match-promo-image"
                    src="{{ asset('assets/img/main-2.jpg') }}"
                    alt=""
                    loading="lazy"
                    decoding="async"
                >
            </div>
            <div class="coffee-match-promo-details">
                <p class="coffee-match-promo-label">Profil rasa</p>
                <div class="coffee-match-promo-stats">
                    <div>
                        <span>01</span>
                        <strong>Roast</strong>
                        <small>Light sampai dark</small>
                    </div>
                    <div>
                        <span>02</span>
                        <strong>Acidity</strong>
                        <small>Rendah sampai tinggi</small>
                    </div>
                    <div>
                        <span>03</span>
                        <strong>Body</strong>
                        <small>Ringan sampai penuh</small>
                    </div>
                </div>
                <p class="coffee-match-promo-note">Setiap pilihan membawa kita lebih dekat ke cangkir yang pas.</p>
            </div>
        </div>
    </div>
</section>

@pushOnce('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/coffee-matcher.css') }}">
@endPushOnce
