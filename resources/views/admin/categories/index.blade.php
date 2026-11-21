@extends('admin.layout')

@section('title', 'Kategori')

@section('content')
    <div class="admin-page-heading">
        <div>
            <p class="admin-eyebrow">Katalog</p>
            <h1>Kategori</h1>
        </div>
        <a class="admin-button admin-button-primary" href="{{ route('admin.categories.create') }}">Tambah kategori</a>
    </div>
    @if ($categories->isEmpty())
        <p>Belum ada kategori.</p>
    @else
        <div class="admin-table-wrap">
        <table class="admin-table">
            <thead>
                <tr><th>Nama</th><th>Slug</th><th>Jumlah produk</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                @foreach ($categories as $category)
                    <tr>
                        <td>{{ $category->name }}</td>
                        <td>{{ $category->slug }}</td>
                        <td>{{ $category->products_count }}</td>
                        <td>
                            <div class="admin-row-actions">
                            <a class="admin-button admin-button-secondary" href="{{ route('admin.categories.edit', $category) }}">Edit</a>
                            <form method="POST" action="{{ route('admin.categories.destroy', $category) }}">
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
