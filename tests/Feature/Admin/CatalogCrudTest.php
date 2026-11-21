<?php

namespace Tests\Feature\Admin;

use App\Models\Category;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CatalogCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_admin_login(): void
    {
        $this->get(route('admin.dashboard'))
            ->assertRedirect(route('admin.login'));
    }

    public function test_authenticated_non_admin_is_forbidden_from_admin_panel(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('admin.dashboard'))
            ->assertForbidden();
    }

    public function test_admin_can_create_update_and_delete_category(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->post(route('admin.categories.store'), ['name' => 'Kopi', 'slug' => 'kopi'])
            ->assertRedirect(route('admin.categories.index'));

        $category = Category::query()->where('slug', 'kopi')->firstOrFail();
        $this->assertModelExists($category);

        $this->actingAs($admin)
            ->get(route('admin.categories.edit', $category))
            ->assertOk();

        $this->actingAs($admin)
            ->put(route('admin.categories.update', $category), ['name' => 'Kopi Pilihan', 'slug' => 'kopi-pilihan'])
            ->assertRedirect(route('admin.categories.index'));

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'Kopi Pilihan',
            'slug' => 'kopi-pilihan',
        ]);

        $this->actingAs($admin)
            ->delete(route('admin.categories.destroy', $category))
            ->assertRedirect(route('admin.categories.index'));

        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    public function test_admin_can_create_update_and_delete_product(): void
    {
        $admin = User::factory()->admin()->create();
        $category = Category::factory()->create();

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk();

        $this->get(route('admin.categories.index'))->assertOk();
        $this->get(route('admin.categories.create'))->assertOk();
        $this->get(route('admin.products.index'))->assertOk();
        $this->get(route('admin.products.create'))->assertOk();

        $this->actingAs($admin)
            ->post(route('admin.products.store'), [
                'category_id' => $category->id,
                'catalog_type' => 'menu',
                'name' => 'Latte',
                'slug' => 'latte',
                'description' => 'Kopi susu.',
                'price' => '25000.00',
                'image_path' => 'assets/img/menu/latte.jpg',
                'is_available' => '1',
                'is_featured' => '0',
            ])
            ->assertRedirect(route('admin.products.index'));

        $product = Product::query()->where('slug', 'latte')->firstOrFail();
        $this->assertModelExists($product);

        $this->actingAs($admin)
            ->get(route('admin.products.edit', $product))
            ->assertOk();

        $this->actingAs($admin)
            ->put(route('admin.products.update', $product), [
                'category_id' => $category->id,
                'catalog_type' => 'menu',
                'name' => 'Es Latte',
                'slug' => 'es-latte',
                'description' => null,
                'price' => '27000.00',
                'image_path' => null,
                'is_available' => '0',
                'is_featured' => '1',
            ])
            ->assertRedirect(route('admin.products.index'));

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Es Latte',
            'slug' => 'es-latte',
            'is_available' => false,
        ]);

        $this->actingAs($admin)
            ->delete(route('admin.products.destroy', $product))
            ->assertRedirect(route('admin.products.index'));

        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    public function test_product_used_by_an_order_cannot_be_deleted(): void
    {
        $admin = User::factory()->admin()->create();
        $product = Product::factory()->create();
        OrderItem::factory()->create(['product_id' => $product->id]);

        $this->actingAs($admin)
            ->delete(route('admin.products.destroy', $product))
            ->assertSessionHas('error');

        $this->assertModelExists($product);
    }

    public function test_invalid_product_data_is_rejected_without_creating_a_product(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->post(route('admin.products.store'), [])
            ->assertSessionHasErrors(['category_id', 'name', 'slug', 'price', 'is_available']);

        $this->assertDatabaseCount('products', 0);
    }

    public function test_admin_can_upload_product_image_and_filter_catalog(): void
    {
        Storage::fake('s3');
        $admin = User::factory()->admin()->create();
        $coffee = Category::factory()->create(['name' => 'Kopi']);
        $snack = Category::factory()->create(['name' => 'Camilan']);

        $this->actingAs($admin)
            ->post(route('admin.products.store'), [
                'category_id' => $coffee->id,
                'catalog_type' => 'menu',
                'name' => 'Latte Pilihan',
                'slug' => 'latte-pilihan',
                'description' => 'Latte baru.',
                'price' => '26000',
                'is_available' => '1',
                'is_featured' => '1',
                'image' => UploadedFile::fake()->createWithContent(
                    'latte.png',
                    base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII=', true),
                ),
            ])
            ->assertRedirect(route('admin.products.index'));

        $product = Product::query()->where('slug', 'latte-pilihan')->firstOrFail();
        $this->assertStringStartsWith('menu/', (string) $product->image_path);
        Storage::disk('s3')->assertExists((string) $product->image_path);

        Product::factory()->for($snack)->create(['name' => 'Roti Cokelat', 'catalog_type' => 'menu']);

        $this->actingAs($admin)
            ->get(route('admin.products.index', ['search' => 'Latte', 'category' => $coffee->id]))
            ->assertOk()
            ->assertSee('Latte Pilihan')
            ->assertDontSee('Roti Cokelat');
    }

    public function test_admin_can_toggle_product_availability(): void
    {
        $admin = User::factory()->admin()->create();
        $product = Product::factory()->create(['is_available' => true]);

        $this->actingAs($admin)
            ->patch(route('admin.products.availability.toggle', $product))
            ->assertRedirect();

        $this->assertFalse($product->refresh()->is_available);
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'is_available' => false,
        ]);
    }

    public function test_category_with_products_cannot_be_deleted(): void
    {
        $admin = User::factory()->admin()->create();
        $product = Product::factory()->create();

        $this->actingAs($admin)
            ->delete(route('admin.categories.destroy', $product->category))
            ->assertSessionHas('error');

        $this->assertModelExists($product->category);
    }

    public function test_admin_login_authenticates_admin_and_redirects_to_dashboard(): void
    {
        User::factory()->admin()->create([
            'email' => 'admin@example.com',
            'password' => 'secret-password',
        ]);

        $this->post(route('admin.login.store'), [
            'email' => 'admin@example.com',
            'password' => 'secret-password',
        ])->assertRedirect(route('admin.dashboard'));

        $this->assertAuthenticated();
    }

    public function test_admin_login_uses_light_neobrutalist_design_and_home_link(): void
    {
        $this->get(route('admin.login'))
            ->assertOk()
            ->assertSee('assets/css/admin.css', false)
            ->assertSee('Kembali ke Kopisthetics / Home')
            ->assertSee(route('home'), false)
            ->assertDontSee('Ingat saya')
            ->assertDontSee('name="remember"', false)
            ->assertDontSee('Sign in with Google')
            ->assertDontSee('Not registered yet')
            ->assertDontSee('2026 DuskCoffee');
    }

    public function test_admin_login_rejects_wrong_credentials(): void
    {
        User::factory()->admin()->create([
            'email' => 'admin@example.com',
            'password' => 'password',
        ]);

        $this->from(route('admin.login'))
            ->post(route('admin.login.store'), [
                'email' => 'admin@example.com',
                'password' => 'wrong-password',
            ])
            ->assertRedirect(route('admin.login'))
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_admin_login_rejects_regular_user(): void
    {
        User::factory()->create([
            'email' => 'customer@example.com',
            'password' => 'password',
            'is_admin' => false,
        ]);

        $this->post(route('admin.login.store'), [
            'email' => 'customer@example.com',
            'password' => 'password',
        ])
            ->assertRedirect()
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_admin_login_rate_limit_rejects_excess_attempts(): void
    {
        for ($attempt = 1; $attempt <= 5; $attempt++) {
            $this->from(route('admin.login'))
                ->post(route('admin.login.store'), [
                    'email' => 'admin@example.com',
                    'password' => 'wrong-password',
                ])
                ->assertRedirect();
        }

        $this->post(route('admin.login.store'), [
            'email' => 'admin@example.com',
            'password' => 'wrong-password',
        ])->assertTooManyRequests();
    }

    public function test_successful_admin_login_regenerates_session_id(): void
    {
        $admin = User::factory()->admin()->create([
            'email' => 'admin@example.com',
            'password' => 'password',
        ]);

        $this->get(route('admin.login'))->assertOk();
        $sessionIdBeforeLogin = $this->app['session']->getId();

        $this->post(route('admin.login.store'), [
            'email' => 'admin@example.com',
            'password' => 'password',
        ])
            ->assertRedirect(route('admin.dashboard'));

        $sessionIdAfterLogin = $this->app['session']->getId();

        $this->assertAuthenticatedAs($admin);
        $this->assertNotSame($sessionIdBeforeLogin, $sessionIdAfterLogin);
    }
}
