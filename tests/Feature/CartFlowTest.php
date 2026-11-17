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
            ->assertSee('Temperature')
            ->assertSee('Add-ons')
            ->assertSee('data-quantity-picker', false)
            ->assertSee('data-quantity-step="-1"', false)
            ->assertSee('name="customization[temperature]"', false)
            ->assertSee('name="customization[sugar_level]"', false)
            ->assertSee('custom-select-chevron', false)
            ->assertSee('href="'.route('home').'"', false);

        $this->post(route('cart.items.store'), [
            'product_id' => $product->id,
            'quantity' => 2,
            'order_type' => 'instant_pickup',
            'customization' => [
                'temperature' => 'cold',
                'sugar_level' => 'low',
                'add_ons' => ['extra_shot'],
            ],
        ])->assertRedirect(route('cart.index'));

        $cart = session('cart');
        $this->assertCount(1, $cart);
        $this->assertSame('cold', array_values($cart)[0]['customization']['temperature']);
        $this->assertSame('instant_pickup', array_values($cart)[0]['order_type']);
        $this->assertSame(['extra_shot'], array_values($cart)[0]['customization']['add_ons']);
        $this->assertSame(33000.0, array_values($cart)[0]['price']);
        $this->get(route('cart.index'))
            ->assertOk()
            ->assertSee('Rp 66.000');
    }

    public function test_product_detail_adds_grind_and_pack_options_to_cart_session(): void
    {
        $product = Product::factory()->create([
            'catalog_type' => 'product',
            'slug' => 'arabika',
        ]);

        $this->get(route('products.show', $product->slug))
            ->assertOk()
            ->assertSee('Grind size')
            ->assertSee('Pack size')
            ->assertSee('type="radio"', false)
            ->assertSee('name="customization[grind_size]"', false)
            ->assertSee('name="customization[pack_size]"', false)
            ->assertSee('data-quantity-picker', false);

        $this->post(route('cart.items.store'), [
            'product_id' => $product->id,
            'quantity' => 1,
            'order_type' => 'expedited_shipping',
            'customization' => [
                'grind_size' => 'coarse',
                'pack_size' => '1kg',
            ],
        ])->assertRedirect(route('cart.index'));

        $this->assertSame('1kg', array_values(session('cart'))[0]['customization']['pack_size']);
        $this->assertSame('coarse', array_values(session('cart'))[0]['customization']['grind_size']);
        $this->assertSame('expedited_shipping', array_values(session('cart'))[0]['order_type']);
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
                'order_type' => 'instant_pickup',
                'customization' => [
                    'temperature' => 'lava',
                    'sugar_level' => 'regular',
                    'add_ons' => ['not-a-real-addon'],
                ],
            ])
            ->assertRedirect(route('menu.show', $product->slug))
            ->assertSessionHasErrors(['customization.temperature', 'customization.add_ons.0']);

        $this->assertSame([], session('cart', []));
    }

    public function test_same_product_and_options_merge_but_different_options_remain_separate(): void
    {
        $product = Product::factory()->create(['catalog_type' => 'menu']);

        foreach (['hot', 'hot', 'cold'] as $temperature) {
            $this->post(route('cart.items.store'), [
                'product_id' => $product->id,
                'quantity' => 1,
                'order_type' => 'instant_pickup',
                'customization' => ['temperature' => $temperature, 'sugar_level' => 'regular'],
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
            'order_type' => 'expedited_shipping',
            'customization' => ['pack_size' => '250g', 'grind_size' => 'whole_bean'],
        ]);
        $key = array_key_first(session('cart'));

        $this->patch(route('cart.items.update', $key), ['quantity' => 3])
            ->assertRedirect(route('cart.index'));

        $this->assertSame(3, session("cart.{$key}.quantity"));
        $this->get(route('cart.index'))
            ->assertOk()
            ->assertSee('data-quantity-picker', false)
            ->assertSee('id="quantity-'.$key.'"', false);

        $this->delete(route('cart.items.destroy', $key))
            ->assertRedirect(route('cart.index'));

        $this->assertSame([], session('cart'));
    }

    public function test_cart_json_endpoints_keep_drawer_count_and_subtotal_in_sync(): void
    {
        $product = Product::factory()->create([
            'catalog_type' => 'menu',
            'price' => '25000.00',
        ]);

        $addResponse = $this->postJson(route('cart.items.store'), [
            'product_id' => $product->id,
            'quantity' => 2,
            'order_type' => 'instant_pickup',
            'customization' => ['temperature' => 'hot', 'sugar_level' => 'regular'],
        ])
            ->assertOk()
            ->assertJsonPath('data.item_count', 2)
            ->assertJsonPath('data.subtotal', 50000);
        $itemKey = $addResponse->json('data.items.0.key');

        $this->getJson(route('cart.summary'))
            ->assertOk()
            ->assertJsonPath('data.item_count', 2)
            ->assertJsonPath('data.items.0.name', $product->name);

        $this->patchJson(route('cart.items.update', $itemKey), ['quantity' => 3])
            ->assertOk()
            ->assertJsonPath('data.item_count', 3)
            ->assertJsonPath('data.subtotal', 75000);

        $this->deleteJson(route('cart.items.destroy', $itemKey))
            ->assertOk()
            ->assertJsonPath('data.item_count', 0)
            ->assertJsonPath('data.items', []);
    }

    public function test_cart_json_endpoint_returns_validation_errors_for_invalid_quantity(): void
    {
        $product = Product::factory()->create(['catalog_type' => 'menu']);

        $this->postJson(route('cart.items.store'), [
            'product_id' => $product->id,
            'quantity' => 100,
            'order_type' => 'instant_pickup',
            'customization' => ['temperature' => 'hot', 'sugar_level' => 'regular'],
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['quantity']);

        $this->assertSame([], session('cart', []));
    }

    public function test_cart_rejects_items_with_a_different_fulfillment_type(): void
    {
        $menu = Product::factory()->create(['catalog_type' => 'menu']);
        $product = Product::factory()->create(['catalog_type' => 'product']);

        $this->post(route('cart.items.store'), [
            'product_id' => $menu->id,
            'quantity' => 1,
            'order_type' => 'instant_pickup',
            'customization' => ['temperature' => 'hot', 'sugar_level' => 'regular'],
        ])->assertRedirect(route('cart.index'));

        $this->postJson(route('cart.items.store'), [
            'product_id' => $product->id,
            'quantity' => 1,
            'order_type' => 'expedited_shipping',
            'customization' => ['grind_size' => 'whole_bean', 'pack_size' => '250g'],
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['order_type']);

        $this->assertCount(1, session('cart'));
    }
}
