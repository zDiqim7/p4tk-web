@extends('admin.layout')

@section('title', 'Login Admin')
@section('body-class', 'admin-page admin-login-page')

@section('content')
    <main class="auth-layout">
        <a class="auth-brand" href="{{ route('home') }}" aria-label="Kopisthetics - kembali ke Home">
            <span class="auth-brand-mark" aria-hidden="true">K</span>
            <span>Kopisthetics <small>ADMIN PANEL</small></span>
        </a>

        <section class="auth-card" aria-labelledby="login-title">
            <div class="auth-card-heading">
                <span class="auth-eyebrow">RUANG ADMIN</span>
                <h1 id="login-title">Sign in</h1>
                <p>Masuk untuk mengelola katalog Kopisthetics.</p>
            </div>

            <form class="auth-form" method="POST" action="{{ route('admin.login.store') }}">
                @csrf
                <div class="auth-field">
                    <label for="email">Email <span aria-hidden="true">*</span></label>
                    <input
                        id="email"
                        name="email"
                        type="email"
                        value="{{ old('email') }}"
                        placeholder="nama@email.com"
                        required
                        autocomplete="username"
                        autofocus
                        @error('email') aria-invalid="true" aria-describedby="email-error" @enderror
                    >
                    @error('email')
                        <p id="email-error" class="auth-error" role="alert">{{ $message }}</p>
                    @enderror
                </div>

                <div class="auth-field">
                    <label for="password">Password <span aria-hidden="true">*</span></label>
                    <input
                        id="password"
                        name="password"
                        type="password"
                        placeholder="Masukkan password"
                        required
                        autocomplete="current-password"
                        @error('password') aria-invalid="true" aria-describedby="password-error" @enderror
                    >
                    @error('password')
                        <p id="password-error" class="auth-error" role="alert">{{ $message }}</p>
                    @enderror
                </div>

                <label class="auth-remember">
                    <input name="remember" type="checkbox" value="1">
                    <span>Ingat saya</span>
                </label>

                <button class="auth-submit" type="submit">
                    Masuk
                    <span aria-hidden="true">→</span>
                </button>
            </form>

            <a class="auth-home-link" href="{{ route('home') }}">
                <span aria-hidden="true">←</span>
                Kembali ke Kopisthetics / Home
            </a>
        </section>

        <span class="auth-decoration auth-decoration-one" aria-hidden="true"></span>
        <span class="auth-decoration auth-decoration-two" aria-hidden="true"></span>
    </main>
@endsection
