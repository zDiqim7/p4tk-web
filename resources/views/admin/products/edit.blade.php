@extends('admin.layout')

@section('title', 'Edit Produk')

@section('content')
    <h1>Edit Produk</h1>
    <form method="POST" action="{{ route('admin.products.update', $product) }}">
        @csrf
        @method('PUT')
        @include('admin.products.form')
    </form>
@endsection
