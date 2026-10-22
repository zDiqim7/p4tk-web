<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_menu_detail_adds_customization_and_add_on_price_to_session_cart(): void
    {
        $product = Product::factory()->create([
            'name' => 'Kopi Susu',
            'catalog_type' => 'menu',
            'price' => '25000.00',
        ]);

        $this->get(route('menu.show', $product->slug))
            ->assertOk()
            ->assertSee('Pilihan penyajian')
            ->assertSee('Add-ons')
            ->assertDontSee('Beranda');

        $this->post(route('cart.items.store'), [
            'product_id' => $product->id,
            'quantity' => 2,
            'customization' => [
                'server_option' => 'cold',
                'add_ons' => ['extra_shot'],
            ],
        ])->assertRedirect(route('cart.index'));

        $cart = session('cart');
        $this->assertCount(1, $cart);
        $this->assertSame('cold', array_values($cart)[0]['customization']['server_option']);
        $this->assertSame(['extra_shot'], array_values($cart)[0]['customization']['add_ons']);
        $this->assertSame(33000.0, array_values($cart)[0]['price']);
        $this->get(route('cart.index'))
            ->assertOk()
            ->assertSee('Rp 66.000');
    }

    public function test_product_detail_adds_size_and_variant_to_cart_session(): void
    {
        $product = Product::factory()->create([
            'catalog_type' => 'product',
            'slug' => 'arabika',
        ]);

        $this->get(route('products.show', $product->slug))
            ->assertOk()
            ->assertSee('Ukuran')
            ->assertSee('Varian');

        $this->post(route('cart.items.store'), [
            'product_id' => $product->id,
            'quantity' => 1,
            'customization' => [
                'size' => 'big',
                'variant' => 'ground',
            ],
        ])->assertRedirect(route('cart.index'));

        $this->assertSame('big', array_values(session('cart'))[0]['customization']['size']);
        $this->assertSame('ground', array_values(session('cart'))[0]['customization']['variant']);
    }

    public function test_menu_detail_does_not_show_a_product_of_another_catalog_type(): void
    {
        $product = Product::factory()->create(['catalog_type' => 'product']);

        $this->get(route('menu.show', $product->slug))
            ->assertNotFound();
    }

    public function test_invalid_customization_does_not_add_item_to_cart(): void
    {
        $product = Product::factory()->create(['catalog_type' => 'menu']);

        $this->from(route('menu.show', $product->slug))
            ->post(route('cart.items.store'), [
                'product_id' => $product->id,
                'quantity' => 1,
                'customization' => [
                    'server_option' => 'lava',
                    'add_ons' => ['not-a-real-addon'],
                ],
            ])
            ->assertRedirect(route('menu.show', $product->slug))
            ->assertSessionHasErrors(['customization.server_option', 'customization.add_ons.0']);

        $this->assertSame([], session('cart', []));
    }

    public function test_same_product_and_options_merge_but_different_options_remain_separate(): void
    {
        $product = Product::factory()->create(['catalog_type' => 'menu']);

        foreach (['hot', 'hot', 'cold'] as $serverOption) {
            $this->post(route('cart.items.store'), [
                'product_id' => $product->id,
                'quantity' => 1,
                'customization' => ['server_option' => $serverOption],
            ])->assertRedirect(route('cart.index'));
        }

        $this->assertCount(2, session('cart'));
        $quantities = array_column(session('cart'), 'quantity');
        sort($quantities);
        $this->assertSame([1, 2], $quantities);
    }

    public function test_cart_item_quantity_can_be_updated_and_item_removed(): void
    {
        $product = Product::factory()->create(['catalog_type' => 'product']);
        $this->post(route('cart.items.store'), [
            'product_id' => $product->id,
            'quantity' => 1,
            'customization' => ['size' => 'medium', 'variant' => 'whole_bean'],
        ]);
        $key = array_key_first(session('cart'));

        $this->patch(route('cart.items.update', $key), ['quantity' => 3])
            ->assertRedirect(route('cart.index'));

        $this->assertSame(3, session("cart.{$key}.quantity"));

        $this->delete(route('cart.items.destroy', $key))
            ->assertRedirect(route('cart.index'));

        $this->assertSame([], session('cart'));
    }
}
