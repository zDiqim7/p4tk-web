@extends('layouts.app')

@section('title', 'Produk')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/catalog.css') }}">
@endpush

@section('content')
    @include('catalog.partials.listing', [
        'kind' => 'products',
        'heading' => 'Produk Pilihan',
        'description' => 'Biji kopi dan merchandise pilihan Kopisthetics.',
        'endpoint' => route('v1.products.index'),
        'filtersEndpoint' => route('v1.products.filters'),
        'detailBase' => url('/products'),
    ])
    <script src="{{ asset('assets/js/catalog.js') }}" defer></script>
@endsection
