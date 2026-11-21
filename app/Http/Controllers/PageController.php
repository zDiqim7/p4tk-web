<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Contracts\View\View;

class PageController extends Controller
{
    public function home(): View
    {
        $menuItems = Product::query()
            ->where('catalog_type', 'menu')
            ->where('is_available', true)
            ->orderByDesc('is_featured')
            ->orderBy('name')
            ->limit(4)
            ->get();

        $products = Product::query()
            ->where('catalog_type', 'product')
            ->where('is_available', true)
            ->orderByDesc('is_featured')
            ->orderBy('name')
            ->limit(4)
            ->get();

        return view('welcome', compact('menuItems', 'products'));
    }

    public function menu(): View
    {
        return view('catalog.menu');
    }

    public function products(): View
    {
        return view('catalog.products');
    }

    public function menuDetail(string $slug): View
    {
        return view('catalog.detail', [
            'product' => Product::query()
                ->with('category')
                ->where('catalog_type', 'menu')
                ->where('is_available', true)
                ->where('slug', $slug)
                ->firstOrFail(),
        ]);
    }

    public function productDetail(string $slug): View
    {
        return view('catalog.detail', [
            'product' => Product::query()
                ->with('category')
                ->where('catalog_type', 'product')
                ->where('is_available', true)
                ->where('slug', $slug)
                ->firstOrFail(),
        ]);
    }

    public function explore(): View
    {
        return view('pages.jelajah');
    }

    public function about(): View
    {
        return view('pages.about');
    }
}
