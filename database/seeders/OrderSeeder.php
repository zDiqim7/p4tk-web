<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::query()
            ->where('email', 'test@example.com')
            ->firstOrFail();

        foreach ([
            [
                'order_number' => 'ORD-SAMPLE-0001',
                'order_type' => 'dine_in',
                'payment_method' => 'cash',
                'status' => 'completed',
                'subtotal' => 33000,
                'tax' => 0,
                'total' => 33000,
                'notes' => null,
            ],
            [
                'order_number' => 'ORD-SAMPLE-0002',
                'order_type' => 'takeaway',
                'payment_method' => 'transfer',
                'status' => 'pending',
                'subtotal' => 33000,
                'tax' => 0,
                'total' => 33000,
                'notes' => 'Tidak perlu alat makan.',
            ],
        ] as $order) {
            Order::updateOrCreate(
                ['order_number' => $order['order_number']],
                ['user_id' => $user->id, ...$order],
            );
        }
    }
}
