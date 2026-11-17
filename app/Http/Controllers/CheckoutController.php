<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCheckoutRequest;
use App\Models\Order;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function create(): View
    {
        $cart = session('cart', []);
        $items = collect($cart);
        $subtotal = $items->sum(
            fn (array $item): float => (float) $item['price'] * $item['quantity'],
        );

        return view('checkout.index', [
            'items' => $items,
            'subtotal' => $subtotal,
            'orderType' => $items->first()['order_type'] ?? null,
        ]);
    }

    public function store(StoreCheckoutRequest $request): RedirectResponse
    {
        $cart = session('cart', []);

        if ($cart === []) {
            return redirect()
                ->route('cart.index')
                ->withErrors(['cart' => 'Keranjang masih kosong.']);
        }

        $orderTypes = collect($cart)
            ->pluck('order_type')
            ->unique()
            ->values();
        $validated = $request->validated();

        if ($orderTypes->count() !== 1 || $orderTypes->first() !== $validated['order_type']) {
            return back()
                ->withErrors(['order_type' => 'Tipe pemenuhan tidak sesuai dengan item di keranjang.'])
                ->withInput();
        }

        $order = DB::transaction(function () use ($cart, $validated): Order {
            $guest = new User;
            $guest->forceFill([
                'name' => $validated['name'],
                'email' => 'guest+'.Str::uuid().'@kopisthetics.local',
                'phone' => $validated['phone'],
                'is_guest' => true,
            ]);
            $guest->save();

            $subtotal = collect($cart)->sum(
                fn (array $item): float => (float) $item['price'] * $item['quantity'],
            );
            $order = Order::query()->create([
                'user_id' => $guest->id,
                'order_number' => 'ORD-'.Str::upper(Str::random(12)),
                'order_type' => $validated['order_type'],
                'payment_method' => 'simulation',
                'status' => 'pending',
                'subtotal' => $subtotal,
                'tax' => 0,
                'total' => $subtotal,
                'notes' => json_encode([
                    'email' => $validated['email'] ?? null,
                    'phone' => $validated['phone'],
                    'delivery_address' => $validated['delivery_address'] ?? null,
                    'payment_note' => 'Simulasi checkout; pembayaran online belum diaktifkan.',
                ], JSON_THROW_ON_ERROR),
            ]);

            foreach ($cart as $item) {
                $order->items()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'customization' => $item['customization'],
                ]);
            }

            return $order;
        });

        session()->forget('cart');

        return redirect()
            ->route('cart.index')
            ->with('status', "Pesanan {$order->order_number} berhasil dibuat dan menunggu proses.");
    }
}
