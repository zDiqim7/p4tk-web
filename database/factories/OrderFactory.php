<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $subtotal = fake()->randomFloat(2, 10000, 500000);
        $tax = fake()->randomFloat(2, 0, 50000);

        return [
            'user_id' => User::factory(),
            'order_number' => 'ORD-'.Str::upper(Str::random(12)),
            'order_type' => fake()->randomElement(['instant_pickup', 'instant_delivery', 'expedited_shipping']),
            'payment_method' => fake()->randomElement(['cash', 'transfer', 'card']),
            'status' => fake()->randomElement(['pending', 'processing', 'ready', 'completed']),
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total' => $subtotal + $tax,
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
