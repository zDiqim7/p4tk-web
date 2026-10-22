@extends('layouts.app')

@section('title', 'Menu')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/catalog.css') }}">
@endpush

@section('content')
    @include('catalog.partials.listing', [
        'kind' => 'menu',
        'heading' => 'Menu Kopisthetics',
        'description' => 'Pilihan makanan dan minuman untuk menemani harimu.',
        'endpoint' => route('v1.menu.index'),
        'filtersEndpoint' => route('v1.menu.filters'),
        'detailBase' => url('/menu'),
    ])
    <script src="{{ asset('assets/js/catalog.js') }}" defer></script>
@endsection
