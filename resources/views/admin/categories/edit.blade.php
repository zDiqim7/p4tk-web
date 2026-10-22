@extends('admin.layout')

@section('title', 'Edit Kategori')

@section('content')
    <h1>Edit Kategori</h1>
    <form method="POST" action="{{ route('admin.categories.update', $category) }}">
        @csrf
        @method('PUT')
        @include('admin.categories.form')
    </form>
@endsection
