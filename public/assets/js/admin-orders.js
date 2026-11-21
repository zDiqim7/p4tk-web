(() => {
    const forms = document.querySelectorAll('[data-order-status-form]');
    const statusMessage = document.querySelector('[data-orders-status]');

    forms.forEach((form) => {
        form.addEventListener('submit', async (event) => {
            event.preventDefault();

            const submitButton = form.querySelector('[type="submit"]');
            submitButton.disabled = true;
            statusMessage.textContent = 'Memperbarui status pesanan...';

            try {
                const response = await fetch(form.action, {
                    method: 'PATCH',
                    headers: {
                        Accept: 'application/json',
                        'X-CSRF-TOKEN': form.querySelector('[name="_token"]').value,
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ status: form.dataset.nextStatus }),
                });
                const result = await response.json();

                if (!response.ok) {
                    throw new Error(result.message || `Permintaan gagal (${response.status}).`);
                }

                const card = form.closest('[data-order-card]');
                const statusBadge = card.querySelector('[data-order-status]');
                statusBadge.className = `order-status-badge order-status-${result.data.status}`;
                statusBadge.textContent = result.data.label;
                statusMessage.textContent = result.message;

                if (result.data.next_status) {
                    form.dataset.nextStatus = result.data.next_status;
                    form.querySelector('[name="status"]').value = result.data.next_status;
                    submitButton.textContent = {
                        ready: 'Tandai siap',
                        completed: 'Selesaikan pesanan',
                    }[result.data.next_status];
                    submitButton.disabled = false;
                } else {
                    form.remove();
                }
            } catch (error) {
                statusMessage.textContent = `Status gagal diperbarui: ${error.message}`;
                submitButton.disabled = false;
            }
        });
    });
})();
