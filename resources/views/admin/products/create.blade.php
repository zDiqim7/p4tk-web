@extends('admin.layout')

@section('title', 'Tambah Produk')

@section('content')
    <h1>Tambah Produk</h1>
    <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data" class="admin-product-form">
        @csrf
        @include('admin.products.form')
    </form>
@endsection
