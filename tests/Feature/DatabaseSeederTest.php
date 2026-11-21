<?php

namespace Tests\Feature;

use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DatabaseSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_production_seeding_creates_admin_and_catalog_without_sample_accounts_or_orders(): void
    {
        config([
            'admin.name' => 'Hosting Admin',
            'admin.email' => 'admin@example.test',
            'admin.password' => 'secure-test-password',
            'app.env' => 'production',
        ]);

        $this->seed(DatabaseSeeder::class);

        $this->assertDatabaseHas('users', [
            'email' => 'admin@example.test',
            'is_guest' => false,
            'is_admin' => true,
        ]);
        $this->assertDatabaseMissing('users', ['email' => 'test@example.com']);
        $this->assertDatabaseHas('products', [
            'slug' => 'classic-americano',
            'image_path' => 'menu/drinks/americano.jpg',
        ]);
        $this->assertDatabaseMissing('orders', ['order_number' => 'ORD-SAMPLE-0001']);
    }
}
