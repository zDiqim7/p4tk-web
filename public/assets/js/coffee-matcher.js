(() => {
    const matcher = document.querySelector('[data-coffee-matcher]');

    if (!matcher) {
        return;
    }

    const endpoint = matcher.dataset.productsEndpoint;
    const results = matcher.querySelector('[data-match-results]');
    const status = matcher.querySelector('[data-matcher-status]');
    const filters = [...matcher.querySelectorAll('[data-match-filter]')];
    const flavorButtons = [...matcher.querySelectorAll('[data-flavor-option]')];
    const sampleImage = matcher.querySelector('[data-match-sample-image]');
    const sampleName = matcher.querySelector('[data-match-sample-name]');
    const placeholderImage = '/assets/img/logo/coffee-cup.png';
    const flavorAliases = {
        fruit: ['fruit', 'fruity', 'buah', 'berry', 'berries', 'citrus', 'apple', 'apel'],
        nutty: ['nutty', 'nuts', 'hazelnut', 'almond', 'walnut', 'kacang', 'praline'],
        chocolate: ['chocolate', 'cokelat', 'cocoa', 'cacao', 'kakao'],
        floral: ['floral', 'flower', 'bunga', 'tea', 'teh'],
    };
    const levelLabels = {
        roast_level: { light: 'Light', medium: 'Medium', dark: 'Dark' },
        acidity_level: { low: 'Low', medium: 'Medium', high: 'High' },
        body: { low: 'Light', medium: 'Medium', high: 'Full' },
    };
    const currency = new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        maximumFractionDigits: 0,
    });
    const selectedFlavors = new Set();
    let products = [];
    let loadFailed = false;

    const fetchCatalog = async (catalogType) => {
        let nextUrl = new URL(endpoint, window.location.origin);
        nextUrl.searchParams.set('type', catalogType);
        nextUrl.searchParams.set('per_page', '12');
        const catalogProducts = [];

        while (nextUrl) {
            if (nextUrl.origin !== window.location.origin) {
                throw new Error('Tautan halaman katalog tidak berada di domain aplikasi.');
            }

            const response = await fetch(nextUrl, {
                headers: { Accept: 'application/json' },
            });

            if (!response.ok) {
                throw new Error(`Katalog gagal dimuat (${response.status}).`);
            }

            const payload = await response.json();

            if (!Array.isArray(payload.data)) {
                throw new Error('Format data katalog tidak valid.');
            }

            catalogProducts.push(...payload.data.map((product) => ({
                ...product,
                catalog_type: catalogType,
            })));

            nextUrl = payload.links?.next ? new URL(payload.links.next, window.location.origin) : null;
        }

        return catalogProducts;
    };

    const normalize = (value) => String(value ?? '').trim().toLocaleLowerCase('id-ID');

    const readLevel = (attributes, key) => {
        const possibleKeys = key === 'acidity_level'
            ? ['acidity_level', 'acidity']
            : key === 'body'
                ? ['body', 'body_level']
                : ['roast_level'];
        const value = normalize(possibleKeys.map((attribute) => attributes[attribute]).find(Boolean));

        if (!value) {
            return null;
        }

        if (key === 'roast_level') {
            if (value.includes('light') || value.includes('terang')) {
                return 1;
            }

            if (value.includes('dark') || value.includes('gelap')) {
                return 3;
            }

            return value.includes('medium') || value.includes('sedang') ? 2 : null;
        }

        if (key === 'acidity_level') {
            if (value.includes('low') || value.includes('rendah') || value.includes('mellow')) {
                return 1;
            }

            if (value.includes('high') || value.includes('tinggi') || value.includes('bright')) {
                return 3;
            }

            return value.includes('medium') || value.includes('sedang') || value.includes('balanced') ? 2 : null;
        }

        if (value.includes('light') || value.includes('low') || value.includes('ringan')) {
            return 1;
        }

        if (value.includes('full') || value.includes('high') || value.includes('berat')) {
            return 3;
        }

        return value.includes('medium') || value.includes('sedang') || value.includes('balanced') ? 2 : null;
    };

    const getFlavorText = (attributes) => normalize([
        attributes.coffee_tastes_like,
        attributes.flavor_notes,
        attributes.taste_type,
    ].filter(Boolean).join(' '));

    const scoreProduct = (product, selectedLevels) => {
        const attributes = product.catalog_attributes || {};
        const levels = ['roast_level', 'acidity_level', 'body']
            .map((key) => ({ key, level: readLevel(attributes, key) }))
            .filter(({ level }) => level !== null);

        if (levels.length === 0) {
            return null;
        }

        let points = 0;
        let possiblePoints = 9;

        levels.forEach(({ key, level }) => {
            const selectedLevel = selectedLevels[key];
            points += Math.max(0, 3 - Math.abs(selectedLevel - level));
        });

        const flavorText = getFlavorText(attributes);
        const matchingFlavors = [...selectedFlavors].filter((flavor) => (
            flavorAliases[flavor].some((alias) => flavorText.includes(alias))
        ));

        points += matchingFlavors.length * 3;
        possiblePoints += selectedFlavors.size * 3;

        return {
            product,
            points,
            possiblePoints,
            profileCoverage: levels.length,
        };
    };

    const createElement = (tagName, className, text) => {
        const element = document.createElement(tagName);
        element.className = className;
        element.textContent = text;
        return element;
    };

    const createProductCard = ({ product, profileCoverage }) => {
        const article = document.createElement('article');
        const imageFrame = document.createElement('div');
        const category = product.catalog_type === 'menu' ? 'Menu' : 'Produk';
        const baseRoute = product.catalog_type === 'menu'
            ? matcher.dataset.menuBase
            : matcher.dataset.productBase;
        const detailUrl = `${baseRoute}/${encodeURIComponent(product.slug)}`;
        const link = document.createElement('a');
        const title = createElement('h4', 'matcher-product-title', product.name);
        const description = createElement('p', 'matcher-product-description', product.description || 'Lihat detail profil produk.');
        const metadata = createElement('p', 'matcher-product-metadata', `${category} · Profil tersedia ${profileCoverage}/3`);
        const footer = document.createElement('div');

        article.className = 'matcher-product-card reveal opacity-0 translate-y-8 transition-all duration-500 ease-out';
        imageFrame.className = 'matcher-product-image';

        const image = document.createElement('img');
        image.src = product.image_url || placeholderImage;
        image.alt = product.name;
        image.loading = 'lazy';
        image.onerror = () => {
            image.onerror = null;
            image.src = placeholderImage;
        };
        imageFrame.append(image);

        link.href = detailUrl;
        link.className = 'matcher-product-link';
        link.setAttribute('aria-label', `Lihat detail ${product.name}`);
        link.append(title);
        footer.className = 'matcher-product-footer';
        footer.append(createElement('strong', 'matcher-product-price', currency.format(Number(product.price))));
        article.append(imageFrame, metadata, link, description, footer);

        return article;
    };

    const updateSample = (product, emptyLabel) => {
        sampleImage.onerror = null;
        sampleImage.src = product?.image_url || placeholderImage;
        sampleImage.alt = product?.name || '';
        sampleName.textContent = product?.name || emptyLabel;

        if (product?.image_url) {
            sampleImage.onerror = () => {
                sampleImage.onerror = null;
                sampleImage.src = placeholderImage;
                sampleName.textContent = 'Foto kopi tidak tersedia.';
            };
        }
    };

    const renderMatches = () => {
        if (loadFailed) {
            return;
        }

        const selectedLevels = Object.fromEntries(filters.map((filter) => [
            filter.dataset.matchFilter,
            filter.selectedIndex + 1,
        ]));
        const matches = products
            .map((product) => scoreProduct(product, selectedLevels))
            .filter(Boolean)
            .sort((first, second) => {
                const firstScore = first.possiblePoints ? first.points / first.possiblePoints : 0;
                const secondScore = second.possiblePoints ? second.points / second.possiblePoints : 0;

                return secondScore - firstScore || second.profileCoverage - first.profileCoverage;
            });

        results.replaceChildren();

        if (matches.length === 0) {
            updateSample(null, products.length === 0 ? 'Belum ada data katalog.' : 'Tidak ada hasil untuk profil ini.');
            status.textContent = products.length === 0
                ? 'Belum ada item di katalog.'
                : 'Belum ada kopi dengan profil rasa yang cukup untuk dicocokkan. Lengkapi atribut katalog agar muncul di sini.';
            return;
        }

        updateSample(matches[0].product, '');
        matches.slice(0, 4).forEach((match) => results.append(createProductCard(match)));
        status.textContent = `${matches.length} rekomendasi dengan data profil rasa.`;
        document.dispatchEvent(new Event('scroll-reveal:observe'));
    };

    const updateProfileBars = () => {
        filters.forEach((filter) => {
            const key = filter.dataset.matchFilter;
            const label = levelLabels[key][filter.value];
            const output = matcher.querySelector(`[data-gauge-label="${key}"]`);
            const track = matcher.querySelector(`[data-gauge="${key}"]`);

            if (output) {
                output.textContent = label;
            }

            if (track) {
                const level = filter.selectedIndex + 1;
                track.style.width = `${(level / 3) * 100}%`;
                track.parentElement.setAttribute('aria-valuenow', String(level));
                track.parentElement.setAttribute('aria-valuetext', label);
            }
        });
    };

    filters.forEach((filter) => {
        filter.addEventListener('change', () => {
            updateProfileBars();
            renderMatches();
        });
    });

    flavorButtons.forEach((button) => {
        button.addEventListener('click', () => {
            const flavor = button.dataset.flavorOption;
            const isSelected = selectedFlavors.has(flavor);

            if (isSelected) {
                selectedFlavors.delete(flavor);
            } else {
                selectedFlavors.add(flavor);
            }

            button.setAttribute('aria-pressed', String(!isSelected));
            button.classList.toggle('is-selected', !isSelected);
            renderMatches();
        });
    });

    updateProfileBars();
    Promise.all([fetchCatalog('product'), fetchCatalog('menu')])
        .then((catalogs) => {
            products = catalogs.flat();
            renderMatches();
        })
        .catch((error) => {
            loadFailed = true;
            updateSample(null, 'Katalog belum dapat dimuat.');
            status.textContent = 'Rekomendasi belum dapat dimuat. Coba muat ulang halaman.';
            console.error('Coffee matcher catalog request failed:', error);
        });
})();
