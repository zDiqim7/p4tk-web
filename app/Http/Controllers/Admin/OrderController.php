<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateOrderStatusRequest;
use App\Models\Order;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class OrderController extends Controller
{
    public function index(): View
    {
        return view('admin.orders.index', [
            'orders' => Order::query()
                ->with(['user:id,name,email,phone', 'items.product:id,name,image_path'])
                ->latest()
                ->paginate(20),
        ]);
    }

    public function updateStatus(UpdateOrderStatusRequest $request, Order $order): JsonResponse|RedirectResponse
    {
        $nextStatus = match ($order->status) {
            'pending' => 'processing',
            'processing' => 'ready',
            'ready' => 'completed',
            default => null,
        };
        $requestedStatus = $request->validated('status');

        if ($requestedStatus !== $nextStatus) {
            $message = 'Status pesanan hanya dapat maju satu tahap pada satu waktu.';

            if ($request->expectsJson()) {
                return response()->json(['message' => $message], 422);
            }

            return back()->withErrors(['status' => $message]);
        }

        $order->update(['status' => $requestedStatus]);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Status pesanan diperbarui.',
                'data' => [
                    'status' => $order->status,
                    'label' => ucfirst($order->status),
                    'next_status' => match ($order->status) {
                        'processing' => 'ready',
                        'ready' => 'completed',
                        default => null,
                    },
                ],
            ]);
        }

        return back()->with('status', 'Status pesanan diperbarui.');
    }
}
