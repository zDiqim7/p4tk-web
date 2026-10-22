@extends('admin.layout')

@section('title', 'Tambah Produk')

@section('content')
    <h1>Tambah Produk</h1>
    <form method="POST" action="{{ route('admin.products.store') }}">
        @csrf
        @include('admin.products.form')
    </form>
@endsection
