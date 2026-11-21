<div class="cart-drawer-shell" data-cart-drawer hidden>
    <button class="cart-drawer-backdrop" type="button" data-cart-close aria-label="Tutup keranjang"></button>
    <aside
        class="cart-drawer"
        id="cart-drawer"
        role="dialog"
        aria-modal="true"
        aria-labelledby="cart-drawer-title"
        tabindex="-1"
        data-cart-summary-url="{{ route('cart.summary') }}"
        data-cart-update-url="{{ route('cart.items.update', ['key' => '__KEY__']) }}"
        data-cart-delete-url="{{ route('cart.items.destroy', ['key' => '__KEY__']) }}"
    >
        <header class="cart-drawer-header">
            <div>
                <p class="cart-drawer-eyebrow">KOPISTHETICS</p>
                <h2 id="cart-drawer-title">Keranjangmu</h2>
            </div>
            <button class="cart-drawer-close" type="button" data-cart-close aria-label="Tutup keranjang">×</button>
        </header>
        <p class="cart-drawer-status" data-cart-status role="status" aria-live="polite">Memuat keranjang...</p>
        <div class="cart-drawer-items" data-cart-items></div>
        <footer class="cart-drawer-footer">
            <div class="cart-drawer-subtotal">
                <span>Subtotal</span>
                <strong data-cart-subtotal>Rp 0</strong>
            </div>
            <a class="cart-drawer-checkout" href="{{ route('checkout.index') }}">Checkout</a>
            <a class="cart-drawer-view-cart" href="{{ route('cart.index') }}">Lihat halaman keranjang</a>
        </footer>
    </aside>
</div>
