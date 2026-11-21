@extends('admin.layout')

@section('title', 'Dashboard Pesanan')

@section('content')
    <header class="orders-heading">
        <p class="admin-eyebrow">KITCHEN / FULFILLMENT</p>
        <h1>Dashboard Pesanan</h1>
        <p>Perbarui status pesanan satu tahap pada satu waktu.</p>
    </header>

    <div class="orders-status-message" data-orders-status role="status" aria-live="polite"></div>

    <section class="orders-list" aria-label="Daftar pesanan">
        @forelse ($orders as $order)
            @php
                $nextStatus = match ($order->status) {
                    'pending' => 'processing',
                    'processing' => 'ready',
                    'ready' => 'completed',
                    default => null,
                };
                $notes = json_decode($order->notes ?? '', true) ?: [];
            @endphp
            <article class="admin-order-card" data-order-card>
                <header class="admin-order-heading">
                    <div>
                        <p class="admin-order-number">{{ $order->order_number }}</p>
                        <p class="admin-order-date">{{ $order->created_at->format('d M Y, H:i') }}</p>
                    </div>
                    <span class="order-status-badge order-status-{{ $order->status }}" data-order-status>
                        {{ ucfirst($order->status) }}
                    </span>
                </header>

                <div class="admin-order-customer">
                    <strong>{{ $order->user?->name ?? 'Guest' }}</strong>
                    @if (! empty($notes['email']))
                        <span>{{ $notes['email'] }}</span>
                    @endif
                    <span>{{ $order->user?->phone ?? $notes['phone'] ?? 'No phone' }}</span>
                    <span>
                        {{ match ($order->order_type) {
                            'instant_pickup' => 'Pick-up',
                            'instant_delivery' => 'Instant delivery',
                            'expedited_shipping' => 'Regular shipping',
                            default => $order->order_type,
                        } }}
                    </span>
                    @if (! empty($notes['delivery_address']))
                        <span>{{ $notes['delivery_address'] }}</span>
                    @endif
                </div>

                <ul class="admin-order-items">
                    @foreach ($order->items as $item)
                        <li>
                            <span>{{ $item->product?->name ?? 'Product removed' }} × {{ $item->quantity }}</span>
                            @if ($item->customization)
                                <small>{{ collect($item->customization)->map(fn ($value, $key) => is_array($value) ? $key.': '.implode(', ', $value) : $key.': '.$value)->join(' · ') }}</small>
                            @endif
                        </li>
                    @endforeach
                </ul>

                <footer class="admin-order-footer">
                    <strong>Total Rp {{ number_format((float) $order->total, 0, ',', '.') }}</strong>
                    @if ($nextStatus)
                        <form
                            method="POST"
                            action="{{ route('admin.orders.status.update', $order) }}"
                            data-order-status-form
                            data-next-status="{{ $nextStatus }}"
                        >
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="{{ $nextStatus }}">
                            <button class="admin-button admin-button-primary" type="submit">
                                {{ match ($nextStatus) {
                                    'processing' => 'Mulai proses',
                                    'ready' => 'Tandai siap',
                                    'completed' => 'Selesaikan pesanan',
                                } }}
                            </button>
                        </form>
                    @endif
                </footer>
            </article>
        @empty
            <p class="admin-empty-state">Belum ada pesanan.</p>
        @endforelse
    </section>

    {{ $orders->links() }}

    <script src="{{ asset('assets/js/admin-orders.js') }}" defer></script>
@endsection
