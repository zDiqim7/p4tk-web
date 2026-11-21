(() => {
    const catalog = document.querySelector('[data-catalog]');

    if (!catalog) {
        return;
    }

    const endpoint = catalog.dataset.productsEndpoint;
    const filtersEndpoint = catalog.dataset.filtersEndpoint;
    const detailBase = catalog.dataset.detailBase;
    const pageSize = Number(catalog.dataset.pageSize || 12);
    const status = document.querySelector('[data-catalog-status]');
    const categoryFilter = document.querySelector('[data-category-filter]');
    const facetFields = document.querySelector('[data-facet-fields]');
    const facetLabels = {
        type: 'Type',
        roast_level: 'Roast Level',
        taste_type: 'Taste Type',
        coffee_tastes_like: 'Coffee Tastes Like',
        region: 'Region',
        origin: 'Origin',
        process: 'Process',
    };
    const sortFilter = document.querySelector('[data-sort-filter]');
    const loadMoreButton = document.querySelector('[data-load-more]');
    const clearFiltersButton = document.querySelector('[data-clear-filters]');
    const currency = new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        maximumFractionDigits: 0,
    });
    let currentPage = 1;
    let lastPage = 1;
    let isLoading = false;
    let activeController;

    const fetchData = async (url, signal) => {
        const response = await fetch(url, {
            headers: { Accept: 'application/json' },
            signal,
        });

        if (!response.ok) {
            throw new Error(`Permintaan gagal (${response.status}).`);
        }

        return response.json();
    };

    const appendProduct = (product) => {
        const card = document.createElement('article');
        const imageLink = document.createElement('a');
        const imageFrame = document.createElement('div');
        const title = document.createElement('h2');
        const category = document.createElement('p');
        const description = document.createElement('p');
        const footer = document.createElement('div');
        const price = document.createElement('span');
        const detailLink = document.createElement('a');

        card.className = 'catalog-card';
        imageLink.className = 'catalog-card-link';
        imageLink.href = `${detailBase}/${encodeURIComponent(product.slug)}`;
        imageLink.setAttribute('aria-label', `Lihat detail ${product.name}`);
        imageFrame.className = 'catalog-card-media';

        const image = document.createElement('img');
        image.src = product.image_url || '/assets/img/logo/coffee-cup.png';
        image.alt = product.name;
        image.loading = 'lazy';
        image.onerror = () => {
            image.onerror = null;
            image.src = '/assets/img/logo/coffee-cup.png';
        };
        imageFrame.append(image);

        title.className = 'catalog-card-title';
        title.textContent = product.name;
        category.className = 'catalog-card-category';
        category.textContent = product.category?.name || '';
        description.className = 'catalog-card-description';
        description.textContent = product.description || '';
        footer.className = 'catalog-card-footer';
        price.textContent = currency.format(Number(product.price));
        detailLink.className = 'catalog-order-link';
        detailLink.href = imageLink.href;
        detailLink.textContent = 'Pesan';
        footer.append(price, detailLink);
        imageLink.append(imageFrame);
        card.append(imageLink, category, title, description, footer);
        catalog.append(card);
    };

    const loadFilters = async () => {
        if (!filtersEndpoint) {
            return;
        }

        const { data: filters } = await fetchData(filtersEndpoint);

        filters.categories.forEach((category) => {
            const option = document.createElement('option');
            option.value = category.slug;
            option.textContent = category.name;
            categoryFilter.append(option);
        });

        Object.entries(facetLabels).forEach(([key, label]) => {
            const options = filters.facets[key] || [];

            if (options.length === 0) {
                return;
            }

            const field = document.createElement('div');
            const fieldLabel = document.createElement('label');
            const selectWrapper = document.createElement('div');
            const select = document.createElement('select');
            const chevron = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
            const chevronPath = document.createElementNS('http://www.w3.org/2000/svg', 'path');
            const allOption = document.createElement('option');

            field.className = 'catalog-filter-field';
            selectWrapper.className = 'custom-select';
            fieldLabel.htmlFor = `catalog-filter-${key}`;
            fieldLabel.textContent = label;
            select.id = `catalog-filter-${key}`;
            select.dataset.facetFilter = key === 'type' ? 'product_type' : key;
            chevron.classList.add('custom-select-chevron');
            chevron.setAttribute('viewBox', '0 0 16 16');
            chevron.setAttribute('aria-hidden', 'true');
            chevron.setAttribute('focusable', 'false');
            chevronPath.setAttribute('d', 'm3 6 5 5 5-5');
            chevronPath.setAttribute('fill', 'none');
            chevronPath.setAttribute('stroke', 'currentColor');
            chevronPath.setAttribute('stroke-width', '2.5');
            chevronPath.setAttribute('stroke-linecap', 'square');
            chevronPath.setAttribute('stroke-linejoin', 'miter');
            chevron.append(chevronPath);
            allOption.value = '';
            allOption.textContent = `Semua ${label.toLowerCase()}`;
            select.append(allOption);

            options.forEach((value) => {
                const option = document.createElement('option');

                option.value = value;
                option.textContent = value;
                select.append(option);
            });

            selectWrapper.append(select, chevron);
            field.append(fieldLabel, selectWrapper);
            facetFields.append(field);
            select.addEventListener('change', () => loadProducts());
        });
    };

    const loadProducts = async ({ append = false } = {}) => {
        if (isLoading && append) {
            return;
        }

        activeController?.abort();
        const controller = new AbortController();
        activeController = controller;
        isLoading = true;
        status.textContent = append ? 'Memuat item berikutnya...' : 'Memuat katalog...';
        loadMoreButton.disabled = true;

        if (!append) {
            currentPage = 1;
            catalog.replaceChildren();
            loadMoreButton.hidden = true;
        }

        try {
            const url = new URL(endpoint, window.location.origin);
            const requestedPage = append ? currentPage + 1 : 1;
            url.searchParams.set('page', String(requestedPage));
            url.searchParams.set('per_page', String(pageSize));
            url.searchParams.set('sort', sortFilter?.value || 'featured');

            if (categoryFilter?.value) {
                url.searchParams.set('category', categoryFilter.value);
            }

            document.querySelectorAll('[data-facet-filter]').forEach((filter) => {
                if (filter.value) {
                    url.searchParams.set(filter.dataset.facetFilter, filter.value);
                }
            });

            const { data: products, meta } = await fetchData(url, controller.signal);

            if (controller.signal.aborted) {
                return;
            }

            products.forEach(appendProduct);
            currentPage = requestedPage;
            lastPage = meta.last_page;
            loadMoreButton.hidden = currentPage >= lastPage;
            status.textContent = products.length === 0 && !append
                ? 'Tidak ada item yang cocok dengan filter.'
                : `${meta.total} item tersedia. Menampilkan ${catalog.childElementCount}.`;
        } catch (error) {
            if (error.name !== 'AbortError') {
                status.textContent = `Katalog gagal dimuat: ${error.message}`;
            }
        } finally {
            if (activeController === controller) {
                isLoading = false;
                loadMoreButton.disabled = false;
            }
        }
    };

    loadMoreButton.addEventListener('click', () => {
        if (currentPage < lastPage) {
            loadProducts({ append: true });
        }
    });

    categoryFilter?.addEventListener('change', () => loadProducts());
    sortFilter?.addEventListener('change', () => loadProducts());
    clearFiltersButton?.addEventListener('click', () => {
        if (categoryFilter) {
            categoryFilter.value = '';
        }
        if (sortFilter) {
            sortFilter.value = 'featured';
        }
        document.querySelectorAll('[data-facet-filter]').forEach((filter) => {
            filter.value = '';
        });
        loadProducts();
    });

    loadFilters()
        .then(() => loadProducts())
        .catch((error) => {
            status.textContent = `Filter katalog gagal dimuat: ${error.message}`;
        });
})();
