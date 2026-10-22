@extends('admin.layout')

@section('title', 'Tambah Kategori')

@section('content')
    <h1>Tambah Kategori</h1>
    <form method="POST" action="{{ route('admin.categories.store') }}">
        @csrf
        @include('admin.categories.form')
    </form>
@endsection
