@extends('layouts.app')

@section('title', 'Cerita Kami')

@section('content')
    <main>
        <!-- Hero Cerita Kami -->
        <section class="border-b-2 border-black px-6 py-16 sm:px-8">
            <div class="mx-auto grid max-w-6xl items-center gap-12 md:grid-cols-2 lg:gap-16">
                <div class="fade-up max-w-xl">
                    <p class="mb-5 inline-block border-2 border-black bg-[var(--card)] px-3 py-2 text-sm font-bold uppercase shadow-[3px_3px_0_0_#000]">
                        Cerita Kami <span aria-hidden="true">·</span> Sejak 1997
                    </p>
                    <h1 class="reveal opacity-0 translate-y-8 transition-all duration-500 ease-out text-4xl font-black leading-tight sm:text-5xl lg:text-6xl">
                        Berawal dari <span class="text-[var(--primary)]">Satu Cangkir</span>
                    </h1>
                    <p class="mt-6 max-w-lg leading-7">
                        Kopisthetics bukan cerita tentang kopi yang rumit. Ini cerita tentang kebiasaan sederhana:
                        duduk, ngobrol, dan menikmati satu cangkir yang bikin hari terasa lebih ringan.
                    </p>

                    <div class="mt-8 flex flex-wrap gap-4">
                        <a href="#cerita" class="border-2 border-black bg-[var(--accent-yellow)] px-5 py-3 font-bold text-[var(--accent-yellow-foreground)] shadow-[4px_4px_0_0_#000] transition-transform hover:translate-x-0.5 hover:translate-y-0.5 hover:shadow-[3px_3px_0_0_#000]">
                            Baca Ceritanya
                        </a>
                        <a href="{{ route('menu.index') }}" class="border-2 border-black bg-[var(--primary)] px-5 py-3 font-bold text-[var(--primary-foreground)] shadow-[4px_4px_0_0_#000] transition-transform hover:translate-x-0.5 hover:translate-y-0.5 hover:shadow-[3px_3px_0_0_#000]">
                            Lihat Menu
                        </a>
                    </div>
                </div>

                <div class="fade-up relative mx-auto w-full max-w-xl">
                    <div class="absolute inset-3 translate-x-3 translate-y-3 border-2 border-black bg-[var(--accent-yellow)]"></div>
                    <img
                        src="{{ asset('assets/img/about-us-2.jpg') }}"
                        alt="Barista Kopisthetics menyajikan kopi untuk pelanggan"
                        class="relative aspect-[4/3] w-full border-2 border-black object-cover shadow-[5px_5px_0_0_#000]"
                    >
                </div>
            </div>
        </section>

        <!-- Cerita & Angka -->
        <section id="cerita" class="border-b-2 border-black bg-[var(--primary-foreground)] px-6 py-16 sm:px-8">
            <div class="mx-auto grid max-w-6xl gap-10 lg:grid-cols-[minmax(0,1.15fr)_minmax(0,0.85fr)] lg:gap-16">
                <div>
                    <p class="text-sm font-bold uppercase tracking-[0.3em] text-[var(--primary)]">Our Story</p>
                    <h2 class="reveal opacity-0 translate-y-8 transition-all duration-500 ease-out mt-2 text-3xl font-black uppercase tracking-tight sm:text-4xl">
                        Dari Workshop Kecil di Pabrika Tuman
                    </h2>

                    <div class="mt-6 space-y-4 text-sm leading-7 text-[var(--foreground)] sm:text-base">
                        <p>
                            Semua dimulai pada 1997. Pabrika Tuman Ltd. membuka workshop sangrai kecil dengan satu
                            mesin tua, satu tim yang masih bisa dihitung dengan sebelah tangan, dan satu obsesi:
                            menemukan profil sangrai yang membuat setiap biji terasa paling jujur.
                        </p>
                        <p>
                            Kami belajar dengan cara yang paling tidak nyaman—mencoba, gagal, lalu mencoba lagi.
                            Menakar ulang suhu, mencatat waktu, membandingkan hasil seduh dari hari ke hari. Dari
                            proses itu kami sadar satu hal: rasa yang konsisten tidak lahir dari keberuntungan,
                            tapi dari catatan yang rapi dan tangan yang sabar.
                        </p>
                        <p>
                            Hari ini Kopisthetics berdiri sebagai ruang temu antara kedai, roastery, dan orang-orang
                            yang sekadar ingin kopi enak tanpa harus jadi ahli dulu. Kami tetap memegang prinsip
                            awal: kopi yang baik harus bisa dinikmati siapa saja, kapan saja.
                        </p>
                    </div>
                </div>

                <aside class="h-fit border-2 border-black bg-[var(--card)] p-6 shadow-[5px_5px_0_0_#000] sm:p-8">
                    <h3 class="text-lg font-black uppercase tracking-tight">Kopisthetics dalam Angka</h3>
                    <dl class="mt-6 space-y-5">
                        <div class="border-b-2 border-dashed border-black pb-4">
                            <dt class="text-xs font-bold uppercase tracking-[0.2em] text-[var(--muted-foreground)]">Berdiri sejak</dt>
                            <dd class="mt-1 text-3xl font-black">1997</dd>
                        </div>
                        <div class="border-b-2 border-dashed border-black pb-4">
                            <dt class="text-xs font-bold uppercase tracking-[0.2em] text-[var(--muted-foreground)]">Tahun menyangrai</dt>
                            <dd class="mt-1 text-3xl font-black">29 tahun</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-bold uppercase tracking-[0.2em] text-[var(--muted-foreground)]">Tahap dari biji ke cangkir</dt>
                            <dd class="mt-1 text-3xl font-black">6 tahap</dd>
                        </div>
                    </dl>
                </aside>
            </div>
        </section>

        <!-- Perjalanan -->
        <section class="border-b-2 border-black px-6 py-16 sm:px-8">
            <div class="mx-auto max-w-6xl">
                <div class="reveal opacity-0 translate-y-8 transition-all duration-500 ease-out mx-auto mb-10 max-w-2xl text-center">
                    <p class="pb-2 text-sm font-bold uppercase tracking-[0.3em] text-[var(--primary)]">Perjalanan</p>
                    <h2 class="text-3xl font-black uppercase tracking-tight sm:text-4xl">Titik-Titik yang Membentuk Kami</h2>
                    <p class="mt-3 text-sm font-medium leading-6">
                        Bukan lompatan besar, tapi langkah kecil yang kami ulang sampai jadi kebiasaan.
                    </p>
                </div>

                <ol class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    <li class="news-step-card reveal opacity-0 translate-y-8 transition-all duration-500 ease-out">
                        <span class="news-step-number">1997</span>
                        <h3 class="news-step-title">Workshop Pertama</h3>
                        <p class="news-step-description">Pabrika Tuman Ltd. mulai menyangrai dalam batch kecil untuk kedai-kedai di sekitar.</p>
                    </li>
                    <li class="news-step-card reveal opacity-0 translate-y-8 transition-all duration-500 ease-out">
                        <span class="news-step-number">2008</span>
                        <h3 class="news-step-title">Kedai Pertama</h3>
                        <p class="news-step-description">Kami membuka ruang untuk pelanggan duduk, mencoba, dan memberi tahu kami mana yang kurang pas.</p>
                    </li>
                    <li class="news-step-card reveal opacity-0 translate-y-8 transition-all duration-500 ease-out">
                        <span class="news-step-number">2019</span>
                        <h3 class="news-step-title">Racikan Sendiri</h3>
                        <p class="news-step-description">Lini signature lahir dari catatan bertahun-tahun: takaran yang sama, rasa yang bisa ditebak.</p>
                    </li>
                    <li class="news-step-card reveal opacity-0 translate-y-8 transition-all duration-500 ease-out">
                        <span class="news-step-number">Kini</span>
                        <h3 class="news-step-title">Satu Ruang, Banyak Cerita</h3>
                        <p class="news-step-description">Menu, produk, dan cerita kami kini bisa diakses lebih luas tanpa meninggalkan cara lama kami bekerja.</p>
                    </li>
                </ol>
            </div>
        </section>

        <!-- Prinsip -->
        <section class="border-b-2 border-black bg-[var(--primary-foreground)] px-6 py-16 sm:px-8">
            <div class="mx-auto max-w-6xl">
                <div class="reveal opacity-0 translate-y-8 transition-all duration-500 ease-out mx-auto mb-10 max-w-2xl text-center">
                    <p class="pb-2 text-sm font-bold uppercase tracking-[0.3em] text-[var(--primary)]">Prinsip</p>
                    <h2 class="text-3xl font-black uppercase tracking-tight sm:text-4xl">Yang Tidak Kami Tawar</h2>
                </div>

                <div class="grid gap-6 md:grid-cols-3">
                    <article class="border-2 border-black bg-[var(--card)] p-6 shadow-[5px_5px_0_0_#000]">
                        <div class="flex h-11 w-11 items-center justify-center border-2 border-black bg-[var(--accent-yellow)] shadow-[3px_3px_0_0_#000]">
                            <i data-lucide="bean" class="h-5 w-5" aria-hidden="true"></i>
                        </div>
                        <h3 class="mt-5 text-lg font-black uppercase tracking-tight">Biji yang Jujur</h3>
                        <p class="mt-2 text-sm leading-6 text-[var(--muted-foreground)]">
                            Kami menyebut asal biji, profil sangrai, dan karakter rasanya apa adanya. Kalau asam, kami bilang asam.
                        </p>
                    </article>

                    <article class="border-2 border-black bg-[var(--card)] p-6 shadow-[5px_5px_0_0_#000]">
                        <div class="flex h-11 w-11 items-center justify-center border-2 border-black bg-[var(--accent-pink)] shadow-[3px_3px_0_0_#000]">
                            <i data-lucide="scale" class="h-5 w-5" aria-hidden="true"></i>
                        </div>
                        <h3 class="mt-5 text-lg font-black uppercase tracking-tight">Takaran yang Sama</h3>
                        <p class="mt-2 text-sm leading-6 text-[var(--muted-foreground)]">
                            Resep dicatat, bukan dihafal. Racikan yang kamu suka hari ini harus terasa sama minggu depan.
                        </p>
                    </article>

                    <article class="border-2 border-black bg-[var(--card)] p-6 shadow-[5px_5px_0_0_#000]">
                        <div class="flex h-11 w-11 items-center justify-center border-2 border-black bg-[var(--primary)] text-[var(--primary-foreground)] shadow-[3px_3px_0_0_#000]">
                            <i data-lucide="users" class="h-5 w-5" aria-hidden="true"></i>
                        </div>
                        <h3 class="mt-5 text-lg font-black uppercase tracking-tight">Orang di Balik Cangkir</h3>
                        <p class="mt-2 text-sm leading-6 text-[var(--muted-foreground)]">
                            Barista, roaster, dan kurir kami adalah bagian dari cerita. Kami menyebut nama, bukan cuma jabatan.
                        </p>
                    </article>
                </div>
            </div>
        </section>

        <!-- CTA -->
        <section class="px-6 py-16 sm:px-8">
            <div class="mx-auto max-w-4xl border-2 border-black bg-[var(--accent-yellow)] p-8 text-center text-[var(--accent-yellow-foreground)] shadow-[6px_6px_0_0_#000] sm:p-12">
                <h2 class="reveal opacity-0 translate-y-8 transition-all duration-500 ease-out text-2xl font-black uppercase tracking-tight sm:text-3xl">Sudah Kenal Ceritanya?</h2>
                <p class="mx-auto mt-3 max-w-xl text-sm font-medium leading-6 text-[var(--accent-yellow-foreground)]">
                    Sekarang giliran cangkirmu. Pilih menu untuk diminum di tempat, atau bawa pulang biji kopi untuk diseduh sendiri.
                </p>
                <div class="mt-7 flex flex-wrap justify-center gap-4">
                    <a href="{{ route('menu.index') }}" class="border-2 border-black bg-[var(--card)] px-5 py-3 font-bold text-[var(--foreground)] shadow-[4px_4px_0_0_#000] transition-transform hover:translate-x-0.5 hover:translate-y-0.5 hover:shadow-[3px_3px_0_0_#000]">
                        Lihat Menu
                    </a>
                    <a href="{{ route('products.index') }}" class="border-2 border-black bg-[var(--primary)] px-5 py-3 font-bold text-[var(--primary-foreground)] shadow-[4px_4px_0_0_#000] transition-transform hover:translate-x-0.5 hover:translate-y-0.5 hover:shadow-[3px_3px_0_0_#000]">
                        Lihat Produk
                    </a>
                </div>
            </div>
        </section>
    </main>
@endsection

@pushOnce('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/coffee-matcher.css') }}">
@endPushOnce
