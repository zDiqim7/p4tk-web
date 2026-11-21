@extends('admin.layout')

@section('title', 'Dashboard')

@section('content')
    <div class="admin-page-heading">
        <div>
            <p class="admin-eyebrow">Ringkasan operasional</p>
            <h1>Dashboard Admin</h1>
        </div>
    </div>

    <div class="admin-stat-grid">
        <article class="admin-stat-card admin-stat-yellow">
            <span>Kategori</span>
            <strong>{{ $categoryCount }}</strong>
            <a href="{{ route('admin.categories.index') }}">Kelola kategori</a>
        </article>
        <article class="admin-stat-card admin-stat-blue">
            <span>Produk</span>
            <strong>{{ $productCount }}</strong>
            <a href="{{ route('admin.products.index') }}">Kelola produk</a>
        </article>
        <article class="admin-stat-card admin-stat-green">
            <span>Pesanan</span>
            <strong>{{ $orderCount }}</strong>
            <a href="{{ route('admin.orders.index') }}">Buka pesanan</a>
        </article>
        <article class="admin-stat-card admin-stat-red">
            <span>Menunggu proses</span>
            <strong>{{ $pendingOrderCount }}</strong>
            <a href="{{ route('admin.orders.index') }}">Proses pesanan</a>
        </article>
    </div>
@endsection
