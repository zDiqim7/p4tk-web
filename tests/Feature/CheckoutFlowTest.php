<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_checkout_saves_customization_and_order_for_admin_queue(): void
    {
        $product = Product::factory()->create([
            'name' => 'Arabika Sumatra',
            'catalog_type' => 'product',
            'price' => '85000.00',
            'image_path' => 'assets/img/menu/decaf.jpg',
        ]);
        $this->post(route('cart.items.store'), [
            'product_id' => $product->id,
            'quantity' => 2,
            'order_type' => 'expedited_shipping',
            'customization' => [
                'grind_size' => 'coarse',
                'pack_size' => '1kg',
            ],
        ])->assertRedirect(route('cart.index'));

        $this->get(route('checkout.index'))
            ->assertOk()
            ->assertSee('Pengiriman ekspedisi reguler')
            ->assertSee('170.000');

        $this->post(route('checkout.store'), [
            'order_type' => 'expedited_shipping',
            'name' => 'Dewi Buyer',
            'email' => 'dewi@example.test',
            'phone' => '08123456789',
            'delivery_address' => 'Jl. Kopi No. 1',
        ])->assertRedirect(route('cart.index'));

        $order = Order::query()->with('items')->firstOrFail();
        $this->assertSame('pending', $order->status);
        $this->assertSame('expedited_shipping', $order->order_type);
        $this->assertSame('170000.00', $order->total);
        $this->assertSame('dewi@example.test', json_decode($order->notes, true)['email']);
        $this->assertSame([
            'grind_size' => 'coarse',
            'pack_size' => '1kg',
        ], $order->items->first()->customization);
        $this->assertSame([], session('cart', []));

        $admin = User::factory()->admin()->create();
        $this->actingAs($admin)
            ->get(route('admin.orders.index'))
            ->assertOk()
            ->assertSee($order->order_number)
            ->assertSee('Arabika Sumatra')
            ->assertSee('Dewi Buyer')
            ->assertSee('pending');
    }

    public function test_checkout_requires_delivery_address_for_delivery_order(): void
    {
        $product = Product::factory()->create([
            'catalog_type' => 'menu',
            'price' => '25000.00',
        ]);
        $this->post(route('cart.items.store'), [
            'product_id' => $product->id,
            'quantity' => 1,
            'order_type' => 'instant_delivery',
            'customization' => ['temperature' => 'hot', 'sugar_level' => 'regular'],
        ])->assertRedirect(route('cart.index'));

        $this->from(route('checkout.index'))
            ->post(route('checkout.store'), [
                'order_type' => 'instant_delivery',
                'name' => 'Rani',
                'phone' => '08123456789',
            ])
            ->assertRedirect(route('checkout.index'))
            ->assertSessionHasErrors(['delivery_address']);

        $this->assertSame(0, Order::query()->count());
        $this->assertCount(1, session('cart'));
    }
}
