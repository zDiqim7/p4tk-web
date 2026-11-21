@extends('layouts.app')

@section('title', 'Jelajah Rasa | Kopisthetics')

@section('content')
    <main
        class="coffee-matcher-page"
        data-coffee-matcher
        data-products-endpoint="{{ route('v1.products.index') }}"
        data-product-base="{{ route('products.index') }}"
        data-menu-base="{{ route('menu.index') }}"
    >
        <section class="matcher-intro border-b-2 border-black px-6 py-16 sm:px-8 lg:py-24">
            <div class="mx-auto grid max-w-6xl items-center gap-10 lg:grid-cols-[minmax(0,0.8fr)_minmax(0,1.2fr)]">
                <div class="matcher-intro-copy">
                    <p class="matcher-eyebrow">KOPISTHETICS / COFFEE MATCHER</p>
                    <h1 class="reveal opacity-0 translate-y-8 transition-all duration-500 ease-out">
                        Cari kopi yang terasa seperti kamu.
                    </h1>
                    <p class="matcher-intro-description">
                        Atur profil rasa yang kamu suka. Kami akan mencocokkannya dengan kopi dan menu yang punya data profil di katalog.
                    </p>
                </div>

                <div class="matcher-preview" aria-label="Contoh kopi dari katalog">
                    <div class="matcher-preview-figure">
                        <img
                            class="matcher-preview-image"
                            src="{{ asset('assets/img/logo/coffee-cup.png') }}"
                            alt=""
                            data-match-sample-image
                        >
                    </div>
                    <p class="matcher-preview-name" data-match-sample-name aria-live="polite">Memuat foto kopi...</p>
                    <div class="matcher-gauge-panel" role="group" aria-label="Profil rasa pilihan">
                        <div class="matcher-gauge-row">
                            <div class="matcher-gauge-label">
                                <span>Roast level</span>
                                <strong data-gauge-label="roast_level">Medium</strong>
                            </div>
                            <div
                                class="matcher-gauge-track matcher-gauge-roast"
                                role="meter"
                                aria-label="Tingkat roast"
                                aria-valuemin="1"
                                aria-valuemax="3"
                                aria-valuenow="2"
                                aria-valuetext="Medium"
                            >
                                <span data-gauge="roast_level" style="width: 66.667%"></span>
                            </div>
                        </div>
                        <div class="matcher-gauge-row">
                            <div class="matcher-gauge-label">
                                <span>Acidity</span>
                                <strong data-gauge-label="acidity_level">Medium</strong>
                            </div>
                            <div
                                class="matcher-gauge-track matcher-gauge-acidity"
                                role="meter"
                                aria-label="Acidity"
                                aria-valuemin="1"
                                aria-valuemax="3"
                                aria-valuenow="2"
                                aria-valuetext="Medium"
                            >
                                <span data-gauge="acidity_level" style="width: 66.667%"></span>
                            </div>
                        </div>
                        <div class="matcher-gauge-row">
                            <div class="matcher-gauge-label">
                                <span>Body</span>
                                <strong data-gauge-label="body">Medium</strong>
                            </div>
                            <div
                                class="matcher-gauge-track matcher-gauge-body"
                                role="meter"
                                aria-label="Body"
                                aria-valuemin="1"
                                aria-valuemax="3"
                                aria-valuenow="2"
                                aria-valuetext="Medium"
                            >
                                <span data-gauge="body" style="width: 66.667%"></span>
                            </div>
                        </div>
                        <ul class="matcher-gauge-legend" aria-label="Legenda warna profil">
                            <li><span class="matcher-gauge-legend-roast" aria-hidden="true"></span>Ungu: roast level</li>
                            <li><span class="matcher-gauge-legend-acidity" aria-hidden="true"></span>Biru: acidity</li>
                            <li><span class="matcher-gauge-legend-body" aria-hidden="true"></span>Biru muda: body</li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <div class="matcher-marquee" aria-label="Kopisthetics, brewed for real, no fluff">
            <div class="matcher-marquee-track" aria-hidden="true">
                <span>KOPISTHETICS <b>•</b> BREWED FOR REAL <b>•</b> NO FLUFF <b>•</b></span>
                <span>KOPISTHETICS <b>•</b> BREWED FOR REAL <b>•</b> NO FLUFF <b>•</b></span>
            </div>
        </div>

        <section class="matcher-workspace px-6 py-16 sm:px-8 lg:py-20" aria-labelledby="matcher-title">
            <div class="mx-auto max-w-6xl">
                <div class="matcher-section-heading reveal opacity-0 translate-y-8 transition-all duration-500 ease-out">
                    <p class="matcher-eyebrow">Racik profilmu</p>
                    <h2 id="matcher-title">Mulai dari yang kamu suka.</h2>
                    <p>Pilih tingkat roast, acidity, body, dan notes rasa. Rekomendasi diperbarui langsung dari katalog.</p>
                </div>

                <div class="matcher-panel">
                    <div class="matcher-controls">
                        <label class="matcher-select-field" for="matcher-roast">
                            <span>Roast level</span>
                            <div class="custom-select">
                                <select id="matcher-roast" data-match-filter="roast_level">
                                    <option value="light">Light</option>
                                    <option value="medium" selected>Medium</option>
                                    <option value="dark">Dark</option>
                                </select>
                                <x-select-chevron />
                            </div>
                        </label>
                        <label class="matcher-select-field" for="matcher-acidity">
                            <span>Acidity</span>
                            <div class="custom-select">
                                <select id="matcher-acidity" data-match-filter="acidity_level">
                                    <option value="low">Low</option>
                                    <option value="medium" selected>Medium</option>
                                    <option value="high">High</option>
                                </select>
                                <x-select-chevron />
                            </div>
                        </label>
                        <label class="matcher-select-field" for="matcher-body">
                            <span>Body</span>
                            <div class="custom-select">
                                <select id="matcher-body" data-match-filter="body">
                                    <option value="low">Light</option>
                                    <option value="medium" selected>Medium</option>
                                    <option value="high">Full</option>
                                </select>
                                <x-select-chevron />
                            </div>
                        </label>

                    </div>

                    <fieldset class="matcher-flavor-fieldset">
                        <legend>Notes rasa yang kamu suka <span>(boleh pilih lebih dari satu)</span></legend>
                        <div class="matcher-flavor-grid">
                            <button class="matcher-flavor-card reveal opacity-0 translate-y-8 transition-all duration-500 ease-out" type="button" data-flavor-option="fruit" aria-pressed="false">
                                <span class="matcher-flavor-mark matcher-flavor-fruit" aria-hidden="true">01</span>
                                <span class="matcher-flavor-name">Buah</span>
                                <span class="matcher-flavor-note">Berry, citrus, apel</span>
                            </button>
                            <button class="matcher-flavor-card reveal opacity-0 translate-y-8 transition-all duration-500 ease-out" type="button" data-flavor-option="nutty" aria-pressed="false">
                                <span class="matcher-flavor-mark matcher-flavor-nutty" aria-hidden="true">02</span>
                                <span class="matcher-flavor-name">Nutty</span>
                                <span class="matcher-flavor-note">Kacang, praline</span>
                            </button>
                            <button class="matcher-flavor-card reveal opacity-0 translate-y-8 transition-all duration-500 ease-out" type="button" data-flavor-option="chocolate" aria-pressed="false">
                                <span class="matcher-flavor-mark matcher-flavor-chocolate" aria-hidden="true">03</span>
                                <span class="matcher-flavor-name">Cokelat</span>
                                <span class="matcher-flavor-note">Kakao, karamel</span>
                            </button>
                            <button class="matcher-flavor-card reveal opacity-0 translate-y-8 transition-all duration-500 ease-out" type="button" data-flavor-option="floral" aria-pressed="false">
                                <span class="matcher-flavor-mark matcher-flavor-floral" aria-hidden="true">04</span>
                                <span class="matcher-flavor-name">Floral</span>
                                <span class="matcher-flavor-note">Bunga, teh</span>
                            </button>
                        </div>
                    </fieldset>

                    <section class="matcher-results" aria-labelledby="matcher-results-title">
                        <div class="matcher-results-heading">
                            <div>
                                <p class="matcher-eyebrow">Hasil pencocokan</p>
                                <h3 id="matcher-results-title">Pilihan untuk profilmu</h3>
                            </div>
                            <p data-matcher-status role="status" aria-live="polite">Mengambil profil dari katalog...</p>
                        </div>
                        <div class="matcher-product-grid" data-match-results></div>
                    </section>
                </div>
            </div>
        </section>

        <section class="matcher-how-it-works border-t-2 border-black bg-[var(--primary-foreground)] px-6 py-16 sm:px-8">
            <div class="mx-auto max-w-6xl">
                <div class="matcher-section-heading reveal opacity-0 translate-y-8 transition-all duration-500 ease-out">
                    <p class="matcher-eyebrow">Cara kerjanya</p>
                    <h2>Rasa yang dicatat, bukan ditebak.</h2>
                </div>
                <div class="matcher-process-grid">
                    <article>
                        <span>01</span>
                        <h3>Profil dicatat</h3>
                        <p>Setiap atribut kopi yang tersedia di katalog menjadi dasar pencocokan.</p>
                    </article>
                    <article>
                        <span>02</span>
                        <h3>Pilihan disusun</h3>
                        <p>Preferensi roast, acidity, body, dan notes rasa membentuk profilmu.</p>
                    </article>
                    <article>
                        <span>03</span>
                        <h3>Match ditampilkan</h3>
                        <p>Rekomendasi diperbarui langsung dan menunjukkan kelengkapan data profilnya.</p>
                    </article>
                </div>
            </div>
        </section>
    </main>
@endsection

@pushOnce('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/coffee-matcher.css') }}">
@endPushOnce

@pushOnce('scripts')
    <script src="{{ asset('assets/js/coffee-matcher.js') }}" defer></script>
@endPushOnce
