<?php

namespace Tests\Feature;

use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    public function test_catalog_pages_render_client_side_data_endpoints(): void
    {
        $this->get(route('menu.index'))
            ->assertOk()
            ->assertSee('Menu')
            ->assertSee('assets/js/catalog.js', false)
            ->assertSee('/api/v1/menu', false)
            ->assertSee('/api/v1/menu/filters', false)
            ->assertSee('data-page-size="12"', false)
            ->assertSee('FAQ');

        $this->get(route('products.index'))
            ->assertOk()
            ->assertSee('Produk')
            ->assertSee('assets/js/catalog.js', false)
            ->assertSee('/api/v1/products', false)
            ->assertSee('/api/v1/products/filters', false)
            ->assertSee('FAQ');
    }

    public function test_explore_page_has_a_named_public_route(): void
    {
        $this->get(route('explore'))
            ->assertOk()
            ->assertSee('Jelajahi DoomCoffee');
    }
}
