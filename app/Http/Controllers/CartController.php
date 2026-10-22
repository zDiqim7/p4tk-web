<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCartItemRequest;
use App\Http\Requests\UpdateCartItemRequest;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
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

    public function store(StoreCartItemRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $product = Product::query()
            ->where('is_available', true)
            ->findOrFail($validated['product_id']);

        $customizationRules = $product->catalog_type === 'menu'
            ? [
                'server_option' => ['required', Rule::in(['hot', 'cold'])],
                'add_ons' => ['sometimes', 'array', 'max:3'],
                'add_ons.*' => ['distinct', Rule::in(array_keys(self::ADD_ONS))],
            ]
            : [
                'size' => ['required', Rule::in(['small', 'medium', 'big'])],
                'variant' => ['required', Rule::in(['whole_bean', 'ground', 'espresso'])],
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
            'customization' => $customization,
        ], JSON_THROW_ON_ERROR));
        $cart = session('cart', []);
        $quantity = ($cart[$itemKey]['quantity'] ?? 0) + $validated['quantity'];

        if ($quantity > 99) {
            return back()
                ->withErrors(['quantity' => 'Jumlah dalam keranjang tidak boleh lebih dari 99.'])
                ->withInput();
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
                'name' => $product->name,
                'image_path' => $product->image_path,
                'price' => $unitPrice,
                'quantity' => $validated['quantity'],
                'customization' => $customization,
                'customization_summary' => $this->customizationSummary($product->catalog_type, $customization),
            ];
        }

        session(['cart' => $cart]);

        return redirect()->route('cart.index')->with('status', 'Item berhasil ditambahkan ke keranjang.');
    }

    public function update(UpdateCartItemRequest $request, string $key): RedirectResponse
    {
        $cart = session('cart', []);
        abort_unless(isset($cart[$key]), 404);

        $cart[$key]['quantity'] = $request->validated('quantity');
        session(['cart' => $cart]);

        return redirect()->route('cart.index')->with('status', 'Jumlah item berhasil diperbarui.');
    }

    public function destroy(string $key): RedirectResponse
    {
        $cart = session('cart', []);
        abort_unless(isset($cart[$key]), 404);

        unset($cart[$key]);
        session(['cart' => $cart]);

        return redirect()->route('cart.index')->with('status', 'Item dihapus dari keranjang.');
    }

    /**
     * @param  array<string, mixed>  $options
     */
    private function customizationSummary(string $catalogType, array $options): string
    {
        if ($catalogType === 'product') {
            return sprintf(
                '%s / %s',
                ucfirst($options['size']),
                str_replace('_', ' ', $options['variant']),
            );
        }

        $addOns = collect($options['add_ons'] ?? [])
            ->map(fn (string $addOn): string => self::ADD_ONS[$addOn]['label']);

        return ucfirst($options['server_option']).($addOns->isNotEmpty() ? ' / '.$addOns->join(', ') : '');
    }
}
