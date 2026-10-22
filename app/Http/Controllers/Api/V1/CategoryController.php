<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\CategoryResource;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CategoryController extends Controller
{
    public function menu(): AnonymousResourceCollection
    {
        return $this->categoriesFor('menu');
    }

    public function products(): AnonymousResourceCollection
    {
        return $this->categoriesFor('product');
    }

    public function menuFilters(): JsonResponse
    {
        return $this->filtersFor('menu');
    }

    public function productFilters(): JsonResponse
    {
        return $this->filtersFor('product');
    }

    private function categoriesFor(string $catalogType): AnonymousResourceCollection
    {
        $categories = Category::query()
            ->whereHas('products', fn ($query) => $query
                ->where('catalog_type', $catalogType)
                ->where('is_available', true))
            ->orderBy('name')
            ->get();

        return CategoryResource::collection($categories);
    }

    private function filtersFor(string $catalogType): JsonResponse
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
        $categories = Category::query()
            ->whereHas('products', fn ($query) => $query
                ->where('catalog_type', $catalogType)
                ->where('is_available', true))
            ->orderBy('name')
            ->get();
        $facets = [];

        foreach ($facetKeys as $key) {
            $facets[$key] = Product::query()
                ->where('catalog_type', $catalogType)
                ->where('is_available', true)
                ->whereNotNull("catalog_attributes->{$key}")
                ->select("catalog_attributes->{$key} as facet_value")
                ->distinct()
                ->orderBy('facet_value')
                ->pluck('facet_value')
                ->all();
        }

        return response()->json([
            'data' => [
                'categories' => CategoryResource::collection($categories),
                'facets' => $facets,
            ],
        ]);
    }
}
