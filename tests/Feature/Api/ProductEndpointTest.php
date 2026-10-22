<?php

namespace Tests\Feature\Api;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductEndpointTest extends TestCase
{
    use RefreshDatabase;

    public function test_products_endpoint_returns_available_products_with_category_data(): void
    {
        $category = Category::factory()->create(['name' => 'Kopi']);
        Product::factory()->for($category)->create([
            'name' => 'Latte',
            'slug' => 'latte',
            'catalog_type' => 'product',
            'price' => '25000.00',
            'is_available' => true,
        ]);
        Product::factory()->for($category)->create([
            'name' => 'Kopi Habis',
            'slug' => 'kopi-habis',
            'catalog_type' => 'product',
            'is_available' => false,
        ]);

        $this->getJson(route('v1.products.index'))
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.name', 'Latte')
            ->assertJsonPath('data.0.category.name', 'Kopi')
            ->assertJsonPath('data.0.price', '25000.00');
    }

    public function test_products_endpoint_filters_by_category_and_sorts_by_price(): void
    {
        $coffeeCategory = Category::factory()->create(['slug' => 'kopi']);
        Product::factory()->for($coffeeCategory)->create(['slug' => 'latte', 'catalog_type' => 'product', 'price' => 25000]);
        Product::factory()->for($coffeeCategory)->create(['slug' => 'flat-white', 'catalog_type' => 'product', 'price' => 18000]);
        Product::factory()->create(['slug' => 'tea', 'catalog_type' => 'product']);

        $this->getJson(route('v1.products.index', ['category' => 'kopi', 'sort' => 'price_low_high']))
            ->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonPath('data.0.slug', 'flat-white')
            ->assertJsonPath('data.1.slug', 'latte');
    }

    public function test_menu_endpoint_filters_by_category_and_uses_twelve_item_pages(): void
    {
        $category = Category::factory()->create(['slug' => 'minuman']);

        Product::factory()
            ->count(13)
            ->for($category)
            ->sequence([
                'catalog_type' => 'menu',
                'is_available' => true,
            ])
            ->create();

        $this->getJson(route('v1.menu.index', ['category' => 'minuman', 'per_page' => 12]))
            ->assertOk()
            ->assertJsonCount(12, 'data')
            ->assertJsonPath('meta.current_page', 1)
            ->assertJsonPath('meta.last_page', 2)
            ->assertJsonPath('meta.total', 13);

        $this->getJson(route('v1.menu.index', [
            'category' => 'minuman',
            'per_page' => 12,
            'page' => 2,
        ]))
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('meta.current_page', 2);
    }

    public function test_product_filter_options_include_available_distinct_catalog_facets(): void
    {
        $productCategory = Category::factory()->create(['slug' => 'biji-kopi']);
        $productAttributes = [
            'type' => 'Biji kopi',
            'roast_level' => 'Medium',
            'taste_type' => 'Balanced',
            'coffee_tastes_like' => 'Cokelat',
            'region' => 'Sumatra',
            'origin' => 'Indonesia',
            'process' => 'Washed',
        ];

        Product::factory()->for($productCategory)->create([
            'catalog_type' => 'product',
            'catalog_attributes' => $productAttributes,
        ]);
        Product::factory()->for($productCategory)->create([
            'catalog_type' => 'product',
            'catalog_attributes' => $productAttributes,
        ]);
        Product::factory()->for($productCategory)->create([
            'catalog_type' => 'product',
            'is_available' => false,
            'catalog_attributes' => ['origin' => 'Unavailable origin'],
        ]);

        $menuCategory = Category::factory()->create(['slug' => 'minuman']);
        Product::factory()->for($menuCategory)->create([
            'catalog_type' => 'menu',
            'catalog_attributes' => ['origin' => 'Menu origin'],
        ]);

        $this->getJson(route('v1.products.filters'))
            ->assertOk()
            ->assertJsonPath('data.categories.0.slug', 'biji-kopi')
            ->assertJsonPath('data.facets.type', ['Biji kopi'])
            ->assertJsonPath('data.facets.origin', ['Indonesia'])
            ->assertJsonPath('data.facets.process', ['Washed']);
    }

    public function test_product_endpoint_filters_by_catalog_facet_values(): void
    {
        $category = Category::factory()->create();
        Product::factory()->for($category)->create([
            'slug' => 'sumatra-coffee',
            'catalog_type' => 'product',
            'catalog_attributes' => ['origin' => 'Indonesia', 'roast_level' => 'Medium'],
        ]);
        Product::factory()->for($category)->create([
            'slug' => 'colombia-coffee',
            'catalog_type' => 'product',
            'catalog_attributes' => ['origin' => 'Colombia', 'roast_level' => 'Light'],
        ]);

        $this->getJson(route('v1.products.index', [
            'origin' => 'Indonesia',
            'roast_level' => 'Medium',
        ]))
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.slug', 'sumatra-coffee');
    }

    public function test_public_categories_are_limited_to_the_requested_catalog_type(): void
    {
        $menuCategory = Category::factory()->create(['slug' => 'makanan']);
        Product::factory()->for($menuCategory)->create(['catalog_type' => 'menu', 'is_available' => true]);

        $productCategory = Category::factory()->create(['slug' => 'biji-kopi']);
        Product::factory()->for($productCategory)->create(['catalog_type' => 'product', 'is_available' => true]);

        $this->getJson(route('v1.menu.categories.index'))
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.slug', 'makanan');

        $this->getJson(route('v1.products.categories.index'))
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.slug', 'biji-kopi');
    }

    public function test_per_page_is_capped_at_twelve(): void
    {
        $this->getJson(route('v1.menu.index', ['per_page' => 13]))
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['per_page']);
    }
}
