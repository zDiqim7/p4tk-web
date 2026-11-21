<div>
    <label for="catalog_type">Jenis katalog</label>
    <div class="custom-select">
        <select id="catalog_type" name="catalog_type" required>
            <option value="menu" @selected(old('catalog_type', $product->catalog_type ?? 'menu') === 'menu')>Menu</option>
            <option value="product" @selected(old('catalog_type', $product->catalog_type ?? 'menu') === 'product')>Produk</option>
        </select>
        <x-select-chevron />
    </div>
    @error('catalog_type')
        <p role="alert">{{ $message }}</p>
    @enderror
</div>
<div>
    <label for="category_id">Kategori</label>
    <div class="custom-select">
        <select id="category_id" name="category_id" required>
            <option value="">Pilih kategori</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" @selected((string) old('category_id', $product->category_id ?? '') === (string) $category->id)>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
        <x-select-chevron />
    </div>
    @error('category_id')
        <p role="alert">{{ $message }}</p>
    @enderror
</div>
<div class="admin-form-field">
    <label for="is_featured">Unggulan</label>
    <label class="admin-toggle">
        <input type="checkbox" name="is_featured" value="1" @checked((bool) old('is_featured', $product->is_featured ?? false))>
        <span>Tampilkan sebagai unggulan</span>
    </label>
    @error('is_featured')
        <p class="admin-form-error" role="alert">{{ $message }}</p>
    @enderror
</div>
<div class="admin-form-field">
    <label for="name">Nama</label>
    <input id="name" name="name" value="{{ old('name', $product->name ?? '') }}" required maxlength="255" autocomplete="off">
    @error('name')
        <p role="alert">{{ $message }}</p>
    @enderror
</div>
<div class="admin-form-field">
    <label for="slug">Slug</label>
    <input id="slug" name="slug" value="{{ old('slug', $product->slug ?? '') }}" required maxlength="255" data-admin-slug>
    @error('slug')
        <p role="alert">{{ $message }}</p>
    @enderror
</div>
<div class="admin-form-field">
    <label for="description">Deskripsi</label>
    <textarea id="description" name="description">{{ old('description', $product->description ?? '') }}</textarea>
    @error('description')
        <p role="alert">{{ $message }}</p>
    @enderror
</div>
<div class="admin-form-field">
    <label for="price">Harga</label>
    <input id="price" name="price" type="number" min="0" step="0.01" value="{{ old('price', $product->price ?? '') }}" required>
    @error('price')
        <p role="alert">{{ $message }}</p>
    @enderror
</div>
<div class="admin-form-field admin-image-field">
    <label for="image">Gambar katalog</label>
    <input id="image" name="image" type="file" accept="image/jpeg,image/png,image/webp" data-admin-image-input>
    @if (isset($product) && $product->image_url)
        <div class="admin-image-preview" data-admin-image-preview>
            <img src="{{ $product->image_url }}" alt="Preview {{ $product->name }}">
        </div>
    @else
        <div class="admin-image-preview" data-admin-image-preview>
            <span>Preview gambar</span>
        </div>
    @endif
    @error('image')
        <p class="admin-form-error" role="alert">{{ $message }}</p>
    @enderror
</div>
@if ($errors->has('image_path'))
    <p class="admin-form-error" role="alert">{{ $errors->first('image_path') }}</p>
@endif
<fieldset class="admin-product-attributes" data-admin-product-attributes>
    <legend>Metadata biji kopi</legend>
    <div class="admin-form-grid">
        @foreach ([
            'roast_level' => 'Roast Level',
            'taste_type' => 'Taste Type',
            'coffee_tastes_like' => 'Flavor Notes',
            'origin' => 'Country of Origin',
            'process' => 'Process',
        ] as $key => $label)
            <div class="admin-form-field">
                <label for="catalog-attribute-{{ $key }}">{{ $label }}</label>
                <input
                    id="catalog-attribute-{{ $key }}"
                    name="catalog_attributes[{{ $key }}]"
                    value="{{ old("catalog_attributes.{$key}", $product->catalog_attributes[$key] ?? '') }}"
                >
                @error("catalog_attributes.{$key}")
                    <p class="admin-form-error" role="alert">{{ $message }}</p>
                @enderror
            </div>
        @endforeach
    </div>
</fieldset>
<div class="admin-form-field">
    <label for="is_available">Status</label>
    <div class="custom-select">
        <select id="is_available" name="is_available" required>
            <option value="1" @selected((string) old('is_available', isset($product) ? (int) $product->is_available : 1) === '1')>Tersedia</option>
            <option value="0" @selected((string) old('is_available', isset($product) ? (int) $product->is_available : 1) === '0')>Tidak tersedia</option>
        </select>
        <x-select-chevron />
    </div>
    @error('is_available')
        <p role="alert">{{ $message }}</p>
    @enderror
</div>
<button class="admin-button admin-button-primary" type="submit">Simpan produk</button>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const nameInput = document.getElementById('name');
    const slugInput = document.querySelector('[data-admin-slug]');
    const catalogType = document.getElementById('catalog_type');
    const attributes = document.querySelector('[data-admin-product-attributes]');
    const imageInput = document.querySelector('[data-admin-image-input]');
    const imagePreview = document.querySelector('[data-admin-image-preview]');

    if (nameInput && slugInput && !slugInput.value) {
        nameInput.addEventListener('input', () => {
            slugInput.value = nameInput.value
                .normalize('NFKD')
                .replace(/[\u0300-\u036f]/g, '')
                .toLowerCase()
                .replace(/[^a-z0-9]+/g, '-')
                .replace(/^-|-$/g, '');
        });
    }

    const toggleAttributes = () => {
        if (attributes) {
            attributes.hidden = catalogType?.value !== 'product';
        }
    };

    catalogType?.addEventListener('change', toggleAttributes);
    toggleAttributes();

    imageInput?.addEventListener('change', () => {
        const file = imageInput.files?.[0];
        if (!file || !imagePreview) return;

        const url = URL.createObjectURL(file);
        imagePreview.innerHTML = '';
        const img = document.createElement('img');
        img.src = url;
        img.alt = 'Preview gambar yang dipilih';
        imagePreview.appendChild(img);
    });
});
</script>
