<?php

namespace Tests\Feature\Api;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryEndpointTest extends TestCase
{
    use RefreshDatabase;

    public function test_categories_endpoint_returns_an_empty_array_when_no_available_products_exist(): void
    {
        $this->getJson(route('v1.categories.index'))
            ->assertOk()
            ->assertJsonCount(0, 'data');
    }

    public function test_categories_endpoint_filters_by_catalog_type(): void
    {
        $menuCategory = Category::factory()->create(['slug' => 'menu-kopi']);
        Product::factory()->for($menuCategory)->create([
            'catalog_type' => 'menu',
            'is_available' => true,
        ]);
        $productCategory = Category::factory()->create(['slug' => 'produk-biji']);
        Product::factory()->for($productCategory)->create([
            'catalog_type' => 'product',
            'is_available' => true,
        ]);

        $this->getJson(route('v1.categories.index', ['type' => 'menu']))
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.slug', 'menu-kopi');
    }

    public function test_products_endpoint_returns_an_empty_array_when_catalog_is_empty(): void
    {
        $this->getJson(route('v1.products.index'))
            ->assertOk()
            ->assertJsonCount(0, 'data');
    }

    public function test_categories_endpoint_rejects_an_unknown_catalog_type(): void
    {
        $this->getJson(route('v1.categories.index', ['type' => 'unknown']))
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['type']);
    }
}
