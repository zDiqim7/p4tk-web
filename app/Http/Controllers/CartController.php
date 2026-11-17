<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCartItemRequest;
use App\Http\Requests\UpdateCartItemRequest;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CartController extends Controller
{
    private const array ADD_ONS = [
        'extra_shot' => ['label' => 'Extra shot', 'price' => 8000],
        'oat_milk' => ['label' => 'Oat milk', 'price' => 7000],
        'extra_topping' => ['label' => 'Extra topping', 'price' => 5000],
    ];

    public function index(): View
    {
        $items = session('cart', []);

        return view('cart.index', [
            'items' => $items,
            'subtotal' => collect($items)->sum(
                fn (array $item): float => (float) $item['price'] * $item['quantity'],
            ),
        ]);
    }

    public function summary(): JsonResponse
    {
        return response()->json(['data' => $this->cartData(session('cart', []))]);
    }

    public function store(StoreCartItemRequest $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validated();
        $product = Product::query()
            ->where('is_available', true)
            ->findOrFail($validated['product_id']);

        $expectedOrderTypes = $product->catalog_type === 'menu'
            ? ['instant_pickup', 'instant_delivery']
            : ['expedited_shipping'];

        if (! in_array($validated['order_type'], $expectedOrderTypes, true)) {
            return $this->invalidCartRequest(
                $request,
                'order_type',
                'Pilihan pengiriman tidak sesuai dengan jenis item.',
            );
        }

        $cart = session('cart', []);

        if ($cart !== [] && collect($cart)->contains(
            fn (array $item): bool => ($item['order_type'] ?? null) !== $validated['order_type'],
        )) {
            return $this->invalidCartRequest(
                $request,
                'order_type',
                'Satu keranjang hanya dapat menggunakan satu tipe pemenuhan. Selesaikan atau kosongkan keranjang terlebih dahulu.',
            );
        }

        $customizationRules = $product->catalog_type === 'menu'
            ? [
                'temperature' => ['required', Rule::in(['hot', 'cold'])],
                'sugar_level' => ['required', Rule::in(['none', 'low', 'regular', 'high'])],
                'add_ons' => ['sometimes', 'array', 'max:3'],
                'add_ons.*' => ['distinct', Rule::in(array_keys(self::ADD_ONS))],
            ]
            : [
                'grind_size' => ['required', Rule::in(['whole_bean', 'coarse', 'fine'])],
                'pack_size' => ['required', Rule::in(['250g', '1kg'])],
            ];

        $customization = Validator::make(
            ['customization' => $validated['customization']],
            collect($customizationRules)
                ->mapWithKeys(fn (array $rules, string $field): array => ["customization.{$field}" => $rules])
                ->all(),
        )->validate()['customization'];

        if (isset($customization['add_ons'])) {
            sort($customization['add_ons']);
        }

        $itemKey = hash('sha256', json_encode([
            'product_id' => $product->id,
            'order_type' => $validated['order_type'],
            'customization' => $customization,
        ], JSON_THROW_ON_ERROR));
        $quantity = ($cart[$itemKey]['quantity'] ?? 0) + $validated['quantity'];

        if ($quantity > 99) {
            return $this->invalidCartRequest(
                $request,
                'quantity',
                'Jumlah dalam keranjang tidak boleh lebih dari 99.',
            );
        }

        if (isset($cart[$itemKey])) {
            $cart[$itemKey]['quantity'] = $quantity;
        } else {
            $unitPrice = (float) $product->price;

            foreach ($customization['add_ons'] ?? [] as $addOn) {
                $unitPrice += self::ADD_ONS[$addOn]['price'];
            }

            $cart[$itemKey] = [
                'product_id' => $product->id,
                'catalog_type' => $product->catalog_type,
                'order_type' => $validated['order_type'],
                'name' => $product->name,
                'image_path' => $product->image_path,
                'price' => $unitPrice,
                'quantity' => $validated['quantity'],
                'customization' => $customization,
                'customization_summary' => $this->customizationSummary($product->catalog_type, $customization),
            ];
        }

        session(['cart' => $cart]);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Item berhasil ditambahkan ke keranjang.',
                'data' => $this->cartData($cart),
            ]);
        }

        return redirect()->route('cart.index')->with('status', 'Item berhasil ditambahkan ke keranjang.');
    }

    public function update(UpdateCartItemRequest $request, string $key): JsonResponse|RedirectResponse
    {
        $cart = session('cart', []);
        abort_unless(isset($cart[$key]), 404);

        $cart[$key]['quantity'] = $request->validated('quantity');
        session(['cart' => $cart]);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Jumlah item berhasil diperbarui.',
                'data' => $this->cartData($cart),
            ]);
        }

        return redirect()->route('cart.index')->with('status', 'Jumlah item berhasil diperbarui.');
    }

    public function destroy(Request $request, string $key): JsonResponse|RedirectResponse
    {
        $cart = session('cart', []);
        abort_unless(isset($cart[$key]), 404);

        unset($cart[$key]);
        session(['cart' => $cart]);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Item dihapus dari keranjang.',
                'data' => $this->cartData($cart),
            ]);
        }

        return redirect()->route('cart.index')->with('status', 'Item dihapus dari keranjang.');
    }

    /**
     * @param  array<string, array<string, mixed>>  $cart
     * @return array{items: array<int, array<string, mixed>>, item_count: int, subtotal: float}
     */
    private function cartData(array $cart): array
    {
        $items = collect($cart)
            ->map(fn (array $item, string $key): array => [
                'key' => $key,
                'name' => $item['name'],
                'image_path' => $item['image_path'],
                'price' => (float) $item['price'],
                'quantity' => (int) $item['quantity'],
                'customization_summary' => $item['customization_summary'],
                'customization' => $item['customization'],
                'order_type' => $item['order_type'] ?? null,
            ])
            ->values();

        return [
            'items' => $items->all(),
            'item_count' => (int) $items->sum('quantity'),
            'subtotal' => (float) $items->sum(
                fn (array $item): float => $item['price'] * $item['quantity'],
            ),
        ];
    }

    /**
     * @param  array<string, mixed>  $options
     */
    private function customizationSummary(string $catalogType, array $options): string
    {
        if ($catalogType === 'product') {
            return sprintf(
                '%s / %s',
                $options['pack_size'],
                str_replace('_', ' ', $options['grind_size']),
            );
        }

        $addOns = collect($options['add_ons'] ?? [])
            ->map(fn (string $addOn): string => self::ADD_ONS[$addOn]['label']);

        $summary = ucfirst($options['temperature']).' / Gula '.ucfirst($options['sugar_level']);

        return $summary.($addOns->isNotEmpty() ? ' / '.$addOns->join(', ') : '');
    }

    private function invalidCartRequest(
        Request $request,
        string $field,
        string $message,
    ): JsonResponse|RedirectResponse {
        if ($request->expectsJson()) {
            return response()->json([
                'message' => $message,
                'errors' => [$field => [$message]],
            ], 422);
        }

        return back()->withErrors([$field => $message])->withInput();
    }
}
