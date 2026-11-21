@extends('admin.layout')

@section('title', 'Produk')

@section('content')
    <div class="admin-page-heading">
        <div>
            <p class="admin-eyebrow">Katalog</p>
            <h1>Produk</h1>
        </div>
        <a class="admin-button admin-button-primary" href="{{ route('admin.products.create') }}">Tambah produk</a>
    </div>

    <form method="GET" action="{{ route('admin.products.index') }}" class="admin-filter-bar">
        <div class="admin-filter-field">
            <label for="admin-product-search">Cari produk</label>
            <input id="admin-product-search" name="search" value="{{ $filters['search'] }}" placeholder="Nama produk">
        </div>
        <div class="admin-filter-field">
            <label for="admin-product-category">Kategori</label>
            <div class="custom-select">
                <select id="admin-product-category" name="category">
                    <option value="">Semua kategori</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected((string) $filters['category'] === (string) $category->id)>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                <x-select-chevron />
            </div>
        </div>
        <button class="admin-button admin-button-primary" type="submit">Filter</button>
        @if ($filters['search'] !== '' || $filters['category'] !== '')
            <a class="admin-button admin-button-secondary" href="{{ route('admin.products.index') }}">Reset</a>
        @endif
    </form>

    @if ($products->isEmpty())
        <p class="admin-empty-state">Tidak ada produk yang cocok. Ubah pencarian atau tambah produk baru.</p>
    @else
        <div class="admin-table-wrap">
        <table class="admin-table">
            <thead>
                <tr><th>Gambar</th><th>Nama</th><th>Jenis</th><th>Kategori</th><th>Harga</th><th>Status</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                    <tr>
                        <td>
                            <div class="admin-product-thumbnail">
                                <img src="{{ $product->image_url }}" alt="" onerror="this.onerror=null;this.src='{{ asset('assets/img/logo/coffee-cup.png') }}';">
                            </div>
                        </td>
                        <td><strong>{{ $product->name }}</strong></td>
                        <td>{{ $product->catalog_type === 'menu' ? 'Menu' : 'Produk' }}</td>
                        <td>{{ $product->category->name }}</td>
                        <td>Rp {{ number_format((float) $product->price, 0, ',', '.') }}</td>
                        <td>
                            <span class="admin-status-badge {{ $product->is_available ? 'admin-status-available' : 'admin-status-sold-out' }}">
                                {{ $product->is_available ? 'Tersedia' : 'Stok Habis' }}
                            </span>
                        </td>
                        <td>
                            <div class="admin-row-actions">
                            <a class="admin-button admin-button-secondary" href="{{ route('admin.products.edit', $product) }}">Edit</a>
                            <form method="POST" action="{{ route('admin.products.availability.toggle', $product) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="{{ $product->is_available ? 'admin-button-danger' : 'admin-button-primary' }}">
                                    {{ $product->is_available ? 'Sold out' : 'Aktifkan' }}
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.products.destroy', $product) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit">Hapus</button>
                            </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        </div>
    @endif
@endsection
