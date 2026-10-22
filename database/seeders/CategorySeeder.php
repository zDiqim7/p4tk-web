<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ([
            'makanan' => 'Makanan',
            'minuman' => 'Minuman',
            'camilan' => 'Camilan',
            'biji-kopi' => 'Biji Kopi',
            'merchandise' => 'Merchandise',
        ] as $slug => $name) {
            Category::updateOrCreate(
                ['slug' => $slug],
                ['name' => $name],
            );
        }
    }
}
