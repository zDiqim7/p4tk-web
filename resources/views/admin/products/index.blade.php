@extends('admin.layout')

@section('title', 'Produk')

@section('content')
    <h1>Produk</h1>
    <p><a href="{{ route('admin.products.create') }}">Tambah produk</a></p>
    @if ($products->isEmpty())
        <p>Belum ada produk.</p>
    @else
        <table>
            <thead>
                <tr><th>Nama</th><th>Jenis</th><th>Kategori</th><th>Harga</th><th>Tersedia</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                    <tr>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->catalog_type === 'menu' ? 'Menu' : 'Produk' }}</td>
                        <td>{{ $product->category->name }}</td>
                        <td>Rp {{ $product->price }}</td>
                        <td>{{ $product->is_available ? 'Ya' : 'Tidak' }}</td>
                        <td>
                            <a href="{{ route('admin.products.edit', $product) }}">Edit</a>
                            <form method="POST" action="{{ route('admin.products.destroy', $product) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection
