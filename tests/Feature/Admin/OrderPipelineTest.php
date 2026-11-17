<?php

namespace Tests\Feature\Admin;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderPipelineTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_advance_an_order_to_the_next_pipeline_status(): void
    {
        $admin = User::factory()->admin()->create();
        $order = Order::factory()->create(['status' => 'pending']);

        $this->actingAs($admin)
            ->patchJson(route('admin.orders.status.update', $order), ['status' => 'processing'])
            ->assertOk()
            ->assertJsonPath('data.status', 'processing')
            ->assertJsonPath('data.next_status', 'ready');

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'processing',
        ]);

        $this->patchJson(route('admin.orders.status.update', $order), ['status' => 'ready'])
            ->assertOk()
            ->assertJsonPath('data.status', 'ready')
            ->assertJsonPath('data.next_status', 'completed');

        $this->patchJson(route('admin.orders.status.update', $order), ['status' => 'completed'])
            ->assertOk()
            ->assertJsonPath('data.status', 'completed')
            ->assertJsonPath('data.next_status', null);

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'completed',
        ]);
    }

    public function test_admin_cannot_skip_an_order_pipeline_status(): void
    {
        $admin = User::factory()->admin()->create();
        $order = Order::factory()->create(['status' => 'pending']);

        $this->actingAs($admin)
            ->patchJson(route('admin.orders.status.update', $order), ['status' => 'ready'])
            ->assertUnprocessable()
            ->assertJsonPath('message', 'Status pesanan hanya dapat maju satu tahap pada satu waktu.');

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'pending',
        ]);
    }

    public function test_non_admin_cannot_access_the_order_dashboard(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('admin.orders.index'))
            ->assertForbidden();
    }

    public function test_admin_order_dashboard_escapes_guest_contact_content(): void
    {
        $admin = User::factory()->admin()->create();
        $customer = User::factory()->create([
            'name' => '<script>alert(1)</script>',
        ]);
        Order::factory()->create([
            'user_id' => $customer->id,
            'notes' => json_encode([
                'email' => '<img src=x onerror=alert(1)>@example.test',
                'phone' => '08123456789',
                'delivery_address' => '<svg onload=alert(1)>',
            ], JSON_THROW_ON_ERROR),
        ]);

        $this->actingAs($admin)
            ->get(route('admin.orders.index'))
            ->assertOk()
            ->assertSee('&lt;script&gt;alert(1)&lt;/script&gt;', false)
            ->assertSee('&lt;img src=x onerror=alert(1)&gt;@example.test', false)
            ->assertSee('&lt;svg onload=alert(1)&gt;', false)
            ->assertDontSee('<script>alert(1)</script>', false)
            ->assertDontSee('<svg onload=alert(1)>', false);
    }
}
