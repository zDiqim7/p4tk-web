<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function menu(Request $request): AnonymousResourceCollection
    {
        return $this->catalog($request, 'menu');
    }

    public function products(Request $request): AnonymousResourceCollection
    {
        return $this->catalog($request, 'product');
    }

    private function catalog(Request $request, string $catalogType): AnonymousResourceCollection
    {
        $facetKeys = [
            'type',
            'roast_level',
            'taste_type',
            'coffee_tastes_like',
            'region',
            'origin',
            'process',
        ];
        $rules = [
            'category' => ['sometimes', 'nullable', 'string', 'exists:categories,slug'],
            'sort' => ['sometimes', Rule::in(['featured', 'popular', 'newest', 'price_high_low', 'price_low_high'])],
            'page' => ['sometimes', 'integer', 'min:1'],
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:12'],
        ];

        foreach ($facetKeys as $facetKey) {
            $rules[$facetKey] = ['sometimes', 'string', 'max:255'];
        }

        $validated = $request->validate($rules);

        $products = Product::query()
            ->with('category')
            ->withSum('orderItems', 'quantity')
            ->where('catalog_type', $catalogType)
            ->where('is_available', true)
            ->when(
                ! empty($validated['category']),
                fn ($query) => $query->whereHas(
                    'category',
                    fn ($categoryQuery) => $categoryQuery->where('slug', $validated['category']),
                ),
            );

        foreach ($facetKeys as $facetKey) {
            if (isset($validated[$facetKey])) {
                $products->where("catalog_attributes->{$facetKey}", $validated[$facetKey]);
            }
        }

        match ($validated['sort'] ?? 'featured') {
            'popular' => $products->orderByDesc('order_items_sum_quantity')->orderBy('name'),
            'newest' => $products->orderByDesc('created_at')->orderByDesc('id'),
            'price_high_low' => $products->orderByDesc('price')->orderBy('name'),
            'price_low_high' => $products->orderBy('price')->orderBy('name'),
            default => $products->orderByDesc('is_featured')->orderBy('name'),
        };

        return ProductResource::collection($products->paginate($validated['per_page'] ?? 12)->withQueryString());
    }
}
