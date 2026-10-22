@extends('admin.layout')

@section('title', 'Dashboard')

@section('content')
    <h1>Dashboard Admin</h1>
    <p>Total kategori: {{ $categoryCount }}</p>
    <p>Total produk: {{ $productCount }}</p>
    <p><a href="{{ route('admin.categories.index') }}">Kelola kategori</a></p>
    <p><a href="{{ route('admin.products.index') }}">Kelola produk</a></p>
@endsection
