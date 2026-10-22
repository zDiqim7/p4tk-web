<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call(CategorySeeder::class);

        $products = [
            [
                'category' => 'makanan',
                'name' => 'Nasi Goreng Kampung',
                'slug' => 'nasi-goreng-kampung',
                'description' => 'Nasi goreng dengan bumbu khas dan telur.',
                'price' => 25000,
                'image_path' => 'assets/img/menu/pancake.jpg',
                'catalog_type' => 'menu',
                'is_featured' => true,
            ],
            [
                'category' => 'minuman',
                'name' => 'Kopi Susu Gula Aren',
                'slug' => 'kopi-susu-gula-aren',
                'description' => 'Kopi susu dengan manis gula aren.',
                'price' => 18000,
                'image_path' => 'assets/img/menu/cappuccino.png',
                'catalog_type' => 'menu',
                'is_featured' => true,
            ],
            [
                'category' => 'minuman',
                'name' => 'Es Teh Manis',
                'slug' => 'es-teh-manis',
                'description' => 'Teh segar disajikan dingin.',
                'price' => 8000,
                'image_path' => 'assets/img/menu/iced-tea.jpg',
                'catalog_type' => 'menu',
                'is_featured' => false,
            ],
            [
                'category' => 'camilan',
                'name' => 'Pisang Goreng',
                'slug' => 'pisang-goreng',
                'description' => 'Pisang goreng renyah untuk teman bersantai.',
                'price' => 15000,
                'image_path' => 'assets/img/menu/cookie.jpg',
                'catalog_type' => 'menu',
                'is_featured' => false,
            ],
            [
                'category' => 'biji-kopi',
                'name' => 'Kopi Arabika Pilihan',
                'slug' => 'kopi-arabika-pilihan',
                'description' => 'Biji kopi arabika pilihan untuk seduhan di rumah.',
                'price' => 85000,
                'image_path' => 'assets/img/menu/decaf.jpg',
                'catalog_type' => 'product',
                'is_featured' => true,
                'catalog_attributes' => [
                    'type' => 'Biji kopi',
                    'roast_level' => 'Medium',
                    'taste_type' => 'Balanced',
                    'coffee_tastes_like' => 'Cokelat, karamel',
                    'region' => 'Sumatra',
                    'origin' => 'Indonesia',
                    'process' => 'Washed',
                ],
            ],
            [
                'category' => 'merchandise',
                'name' => 'Tumbler Kopisthetics',
                'slug' => 'tumbler-kopisthetics',
                'description' => 'Tumbler praktis untuk menemani aktivitas harian.',
                'price' => 65000,
                'image_path' => 'assets/img/menu/hot-chocolate.jpg',
                'catalog_type' => 'product',
                'is_featured' => false,
            ],
        ];

        foreach ($products as $product) {
            $category = Category::query()
                ->where('slug', $product['category'])
                ->firstOrFail();

            Product::updateOrCreate(
                ['slug' => $product['slug']],
                [
                    'category_id' => $category->id,
                    'catalog_type' => $product['catalog_type'],
                    'name' => $product['name'],
                    'description' => $product['description'],
                    'price' => $product['price'],
                    'image_path' => $product['image_path'],
                    'is_available' => true,
                    'is_featured' => $product['is_featured'],
                    'catalog_attributes' => $product['catalog_attributes'] ?? null,
                ],
            );
        }
    }
}
