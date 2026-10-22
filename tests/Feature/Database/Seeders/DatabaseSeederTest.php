<?php

namespace Tests\Feature\Database\Seeders;

use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class DatabaseSeederTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_seeding_catalog_creates_sample_records_and_is_repeatable(): void
    {
        $this->seed();
        $this->seed();

        $this->assertDatabaseCount('users', 1);
        $this->assertDatabaseCount('categories', 5);
        $this->assertDatabaseCount('products', 6);
        $this->assertDatabaseCount('orders', 2);
        $this->assertDatabaseCount('order_items', 4);
        $this->assertDatabaseCount('contacts', 2);
    }
}
