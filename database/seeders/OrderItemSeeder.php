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
            ['ORD-SAMPLE-0001', 'nasi-goreng-kampung', 1, 25000],
            ['ORD-SAMPLE-0001', 'es-teh-manis', 1, 8000],
            ['ORD-SAMPLE-0002', 'kopi-susu-gula-aren', 1, 18000],
            ['ORD-SAMPLE-0002', 'pisang-goreng', 1, 15000],
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
