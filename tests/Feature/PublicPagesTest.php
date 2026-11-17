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

    public function test_public_pages_share_global_navigation_footer_and_cart_drawer(): void
    {
        $pages = [
            route('home'),
            route('menu.index'),
            route('products.index'),
            route('explore'),
            route('about'),
        ];

        foreach ($pages as $page) {
            $this->get($page)
                ->assertOk()
                ->assertSee('aria-label="Navigasi utama"', false)
                ->assertSee('href="'.route('home').'"', false)
                ->assertSee('href="'.route('explore').'"', false)
                ->assertSee('href="'.route('menu.index').'"', false)
                ->assertSee('href="'.route('products.index').'"', false)
                ->assertSee('href="'.route('about').'"', false)
                ->assertSee('data-cart-open', false)
                ->assertSee('id="cart-drawer"', false)
                ->assertSee('Pabrika Tuman Ltd.');
        }
    }

    public function test_explore_page_has_a_named_public_route(): void
    {
        $this->assertSame('/jelajah', route('explore', [], false));

        $this->get(route('explore'))
            ->assertOk()
            ->assertSee('Cari kopi yang terasa seperti kamu.')
            ->assertSee('data-coffee-matcher', false)
            ->assertSee('data-products-endpoint="'.route('v1.products.index').'"', false)
            ->assertSee('data-match-filter="roast_level"', false)
            ->assertSee('data-match-filter="acidity_level"', false)
            ->assertSee('data-match-filter="body"', false)
            ->assertSee('data-flavor-option="chocolate"', false)
            ->assertSee('assets/js/coffee-matcher.js', false)
            ->assertSee('assets/js/scroll-reveal.js', false);
    }

    public function test_legacy_explore_url_redirects_to_jelajah(): void
    {
        $this->get('/jelajahi')
            ->assertRedirect(route('explore'));
    }

    public function test_home_page_shows_coffee_matcher_section_between_explore_and_process(): void
    {
        $this->get(route('home'))
            ->assertOk()
            ->assertSeeInOrder([
                'id="jelajahi"',
                'Kenali rasa yang paling cocok buatmu.',
                'id="galeri"',
            ], false)
            ->assertSee('href="'.route('explore').'"', false)
            ->assertSee('class="w-full border-black bg-[var(--card)] py-24"', false);
    }

    public function test_about_page_is_reachable_from_the_home_hero(): void
    {
        $this->get('/tentang-kami')
            ->assertOk()
            ->assertSee('Cerita Kami')
            ->assertSee('1997')
            ->assertSee('assets/img/about-us-2.jpg', false)
            ->assertSee('news-step-card reveal opacity-0 translate-y-8 transition-all duration-500 ease-out', false)
            ->assertSee('assets/js/scroll-reveal.js', false);

        $this->get(route('home'))
            ->assertSee('href="'.route('about').'"', false);
    }

    public function test_checkout_placeholder_uses_global_layout_and_returns_to_cart(): void
    {
        $this->get(route('checkout.index'))
            ->assertOk()
            ->assertSee('Checkout')
            ->assertSee('href="'.route('cart.index').'"', false)
            ->assertSee('id="cart-drawer"', false);
    }
}
