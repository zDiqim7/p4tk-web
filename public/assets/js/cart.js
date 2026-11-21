(() => {
    const drawerShell = document.querySelector('[data-cart-drawer]');

    if (!drawerShell) {
        return;
    }

    const drawer = drawerShell.querySelector('.cart-drawer');
    const itemsContainer = drawerShell.querySelector('[data-cart-items]');
    const status = drawerShell.querySelector('[data-cart-status]');
    const subtotal = drawerShell.querySelector('[data-cart-subtotal]');
    const countBadges = document.querySelectorAll('[data-cart-count]');
    const triggers = document.querySelectorAll('[data-cart-open]');
    const closeButtons = drawerShell.querySelectorAll('[data-cart-close]');
    const formatter = new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        maximumFractionDigits: 0,
    });
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
    let lastFocusedElement;
    let closeTimer;

    const setItemCount = (count) => {
        countBadges.forEach((badge) => {
            badge.textContent = String(count);
            badge.hidden = count === 0;
        });
    };

    const makeButton = (label, action, key, disabled = false) => {
        const button = document.createElement('button');

        button.type = 'button';
        button.textContent = label;
        button.dataset.cartAction = action;
        button.dataset.cartItemKey = key;
        button.disabled = disabled;
        button.setAttribute('aria-label', label === '−' ? 'Kurangi jumlah' : label === '+' ? 'Tambah jumlah' : 'Hapus item');

        return button;
    };

    const renderItems = (items) => {
        itemsContainer.replaceChildren();

        if (items.length === 0) {
            const emptyState = document.createElement('p');
            emptyState.className = 'cart-drawer-empty';
            emptyState.textContent = 'Keranjangmu masih kosong.';
            itemsContainer.append(emptyState);
            return;
        }

        items.forEach((item) => {
            const card = document.createElement('article');
            const details = document.createElement('div');
            const title = document.createElement('h3');
            const customization = document.createElement('p');
            const fulfillment = document.createElement('p');
            const lineTotal = document.createElement('p');
            const actions = document.createElement('div');
            const quantity = document.createElement('span');

            card.className = 'cart-drawer-item';

            const imageSource = item.image_url || item.image_path || '';

            if (imageSource) {
                const isAbsolute = /^https?:\/\//i.test(imageSource);
                const imageUrl = isAbsolute ? imageSource : new URL(imageSource, window.location.origin).href;
                const image = document.createElement('img');

                image.className = 'cart-drawer-item-image';
                image.src = imageUrl;
                image.alt = item.name;
                image.loading = 'lazy';
                image.onerror = () => {
                    image.src = '/assets/img/logo/coffee-cup.png';
                };
                card.append(image);
            }

            if (card.childElementCount === 0) {
                const placeholder = document.createElement('span');
                placeholder.className = 'cart-drawer-item-placeholder';
                placeholder.setAttribute('aria-hidden', 'true');
                placeholder.textContent = 'K';
                card.append(placeholder);
            }

            details.className = 'cart-drawer-item-details';
            title.textContent = item.name;
            customization.textContent = item.customization_summary;
            fulfillment.textContent = {
                instant_pickup: 'Pick-up',
                instant_delivery: 'Instant delivery',
                expedited_shipping: 'Regular shipping',
            }[item.order_type] || '';
            lineTotal.className = 'cart-drawer-item-line-total';
            lineTotal.textContent = formatter.format(item.price * item.quantity);
            details.append(title, customization, fulfillment, lineTotal);

            actions.className = 'cart-drawer-item-actions';
            actions.append(makeButton('−', 'decrease', item.key, item.quantity <= 1));
            quantity.textContent = String(item.quantity);
            actions.append(quantity, makeButton('+', 'increase', item.key));

            const removeButton = makeButton('Hapus', 'remove', item.key);
            removeButton.classList.add('cart-drawer-item-remove');
            actions.append(removeButton);

            card.append(details, actions);
            itemsContainer.append(card);
        });
    };

    const applyCartData = (data, message = '') => {
        renderItems(data.items);
        setItemCount(data.item_count);
        subtotal.textContent = formatter.format(data.subtotal);
        status.textContent = message;
    };

    const readResponse = async (response) => {
        const result = await response.json();

        if (!response.ok) {
            const firstError = Object.values(result.errors || {}).flat()[0];
            throw new Error(firstError || result.message || `Permintaan gagal (${response.status}).`);
        }

        return result;
    };

    const loadCart = async () => {
        status.textContent = 'Memuat keranjang...';

        try {
            const response = await fetch(drawer.dataset.cartSummaryUrl, {
                headers: { Accept: 'application/json' },
            });
            const result = await readResponse(response);
            applyCartData(result.data);
        } catch (error) {
            status.textContent = `Keranjang gagal dimuat: ${error.message}`;
        }
    };

    const openDrawer = () => {
        window.clearTimeout(closeTimer);
        lastFocusedElement = document.activeElement;
        drawerShell.hidden = false;
        requestAnimationFrame(() => drawerShell.classList.add('is-open'));
        document.body.classList.add('cart-drawer-open');
        drawer.focus();
    };

    const closeDrawer = () => {
        drawerShell.classList.remove('is-open');
        document.body.classList.remove('cart-drawer-open');
        window.clearTimeout(closeTimer);
        closeTimer = window.setTimeout(() => {
            drawerShell.hidden = true;
        }, 220);
        lastFocusedElement?.focus();
    };

    const sendCartChange = async (url, method, body) => {
        status.textContent = 'Memperbarui keranjang...';

        const headers = {
            Accept: 'application/json',
            'X-CSRF-TOKEN': csrfToken,
        };
        const options = { method, headers };

        if (body instanceof FormData) {
            options.body = body;
        } else if (body) {
            headers['Content-Type'] = 'application/json';
            options.body = JSON.stringify(body);
        }

        const result = await readResponse(await fetch(url, options));
        applyCartData(result.data, result.message);
    };

    triggers.forEach((trigger) => trigger.addEventListener('click', openDrawer));
    closeButtons.forEach((button) => button.addEventListener('click', closeDrawer));

    document.addEventListener('keydown', (event) => {
        if (drawerShell.hidden) {
            return;
        }

        if (event.key === 'Escape') {
            closeDrawer();
            return;
        }

        if (event.key === 'Tab') {
            const focusableElements = [...drawer.querySelectorAll('a[href], button:not(:disabled)')];
            const firstElement = focusableElements[0];
            const lastElement = focusableElements.at(-1);

            if (event.shiftKey && document.activeElement === firstElement) {
                event.preventDefault();
                lastElement.focus();
            } else if (!event.shiftKey && document.activeElement === lastElement) {
                event.preventDefault();
                firstElement.focus();
            }
        }
    });

    drawer.addEventListener('click', async (event) => {
        if (!(event.target instanceof Element)) {
            return;
        }

        const button = event.target.closest('[data-cart-action]');

        if (!button || button.disabled) {
            return;
        }

        const itemKey = button.dataset.cartItemKey;
        const encodedKey = encodeURIComponent(itemKey);
        const action = button.dataset.cartAction;
        const updateUrl = drawer.dataset.cartUpdateUrl.replace('__KEY__', encodedKey);
        const deleteUrl = drawer.dataset.cartDeleteUrl.replace('__KEY__', encodedKey);

        try {
            if (action === 'remove') {
                await sendCartChange(deleteUrl, 'DELETE');
                return;
            }

            const quantity = Number(button.parentElement.querySelector('span').textContent);
            await sendCartChange(updateUrl, 'PATCH', {
                quantity: quantity + (action === 'increase' ? 1 : -1),
            });
        } catch (error) {
            status.textContent = `Keranjang gagal diperbarui: ${error.message}`;
        }
    });

    document.querySelectorAll('[data-cart-add-form]').forEach((form) => {
        form.addEventListener('submit', async (event) => {
            event.preventDefault();

            const submitButton = form.querySelector('[type="submit"]');
            const feedback = form.querySelector('[data-cart-feedback]');
            submitButton.disabled = true;
            feedback.textContent = 'Menambahkan item...';

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: { Accept: 'application/json' },
                    body: new FormData(form),
                });
                const result = await readResponse(response);
                applyCartData(result.data, result.message);
                feedback.textContent = result.message;
                openDrawer();
            } catch (error) {
                feedback.textContent = `Item gagal ditambahkan: ${error.message}`;
            } finally {
                submitButton.disabled = false;
            }
        });
    });

    loadCart();
})();
