<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Database\Seeder;

class OrderItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            ['ORD-SAMPLE-0001', 'classic-americano', 1, 56250],
            ['ORD-SAMPLE-0001', 'chunky-dark-chocolate-chip-cookie', 1, 52500],
            ['ORD-SAMPLE-0002', 'velvety-cappuccino', 1, 67500],
            ['ORD-SAMPLE-0002', 'crispy-savory-rissoles', 1, 82500],
        ];

        foreach ($items as [$orderNumber, $productSlug, $quantity, $price]) {
            $order = Order::query()
                ->where('order_number', $orderNumber)
                ->firstOrFail();
            $product = Product::query()
                ->where('slug', $productSlug)
                ->firstOrFail();

            OrderItem::updateOrCreate(
                ['order_id' => $order->id, 'product_id' => $product->id],
                ['quantity' => $quantity, 'price' => $price],
            );
        }
    }
}
