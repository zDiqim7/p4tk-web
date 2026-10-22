@extends('admin.layout')

@section('title', 'Kategori')

@section('content')
    <h1>Kategori</h1>
    <p><a href="{{ route('admin.categories.create') }}">Tambah kategori</a></p>
    @if ($categories->isEmpty())
        <p>Belum ada kategori.</p>
    @else
        <table>
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
                            <a href="{{ route('admin.categories.edit', $category) }}">Edit</a>
                            <form method="POST" action="{{ route('admin.categories.destroy', $category) }}">
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
