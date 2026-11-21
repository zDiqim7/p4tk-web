<?php

namespace Tests\Feature\Database\Seeders;

use App\Models\Product;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class DatabaseSeederTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_seeding_catalog_creates_sample_records_and_is_repeatable(): void
    {
        $this->seed();
        $this->seed();

        $this->assertDatabaseCount('users', 2);
        $this->assertDatabaseCount('categories', 5);
        $this->assertDatabaseCount('products', 36);
        $this->assertDatabaseCount('orders', 2);
        $this->assertDatabaseCount('order_items', 4);
        $this->assertDatabaseCount('contacts', 2);

        $this->assertSame(16, Product::query()->where('catalog_type', 'menu')->count());
        $this->assertSame(20, Product::query()->where('catalog_type', 'product')->count());
        $this->assertDatabaseHas('products', [
            'slug' => 'classic-americano',
            'image_path' => 'menu/drinks/americano.jpg',
        ]);
        $this->assertDatabaseHas('products', [
            'slug' => 'red-earth',
            'image_path' => 'products/coffee-beans/terra-roast.jpg',
        ]);
        $this->assertDatabaseHas('products', [
            'slug' => 'floral-drift',
            'image_path' => null,
        ]);

        foreach ([
            'nasi-goreng-kampung',
            'kopi-susu-gula-aren',
            'es-teh-manis',
            'pisang-goreng',
            'tumbler-kopisthetics',
        ] as $removedSlug) {
            $this->assertDatabaseMissing('products', ['slug' => $removedSlug]);
        }
    }
}
