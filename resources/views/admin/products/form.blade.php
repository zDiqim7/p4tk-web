<div>
    <label for="catalog_type">Jenis katalog</label>
    <select id="catalog_type" name="catalog_type" required>
        <option value="menu" @selected(old('catalog_type', $product->catalog_type ?? 'menu') === 'menu')>Menu</option>
        <option value="product" @selected(old('catalog_type', $product->catalog_type ?? 'menu') === 'product')>Produk</option>
    </select>
    @error('catalog_type')
        <p role="alert">{{ $message }}</p>
    @enderror
</div>
<div>
    <label for="category_id">Kategori</label>
    <select id="category_id" name="category_id" required>
        <option value="">Pilih kategori</option>
        @foreach ($categories as $category)
            <option value="{{ $category->id }}" @selected((string) old('category_id', $product->category_id ?? '') === (string) $category->id)>
                {{ $category->name }}
            </option>
        @endforeach
    </select>
    @error('category_id')
        <p role="alert">{{ $message }}</p>
    @enderror
</div>
<div>
    <label for="is_featured">Unggulan</label>
    <select id="is_featured" name="is_featured" required>
        <option value="0" @selected((string) old('is_featured', isset($product) ? (int) $product->is_featured : 0) === '0')>Tidak</option>
        <option value="1" @selected((string) old('is_featured', isset($product) ? (int) $product->is_featured : 0) === '1')>Ya</option>
    </select>
    @error('is_featured')
        <p role="alert">{{ $message }}</p>
    @enderror
</div>
<div>
    <label for="name">Nama</label>
    <input id="name" name="name" value="{{ old('name', $product->name ?? '') }}" required maxlength="255">
    @error('name')
        <p role="alert">{{ $message }}</p>
    @enderror
</div>
<div>
    <label for="slug">Slug</label>
    <input id="slug" name="slug" value="{{ old('slug', $product->slug ?? '') }}" required maxlength="255">
    @error('slug')
        <p role="alert">{{ $message }}</p>
    @enderror
</div>
<div>
    <label for="description">Deskripsi</label>
    <textarea id="description" name="description">{{ old('description', $product->description ?? '') }}</textarea>
    @error('description')
        <p role="alert">{{ $message }}</p>
    @enderror
</div>
<div>
    <label for="price">Harga</label>
    <input id="price" name="price" type="number" min="0" step="0.01" value="{{ old('price', $product->price ?? '') }}" required>
    @error('price')
        <p role="alert">{{ $message }}</p>
    @enderror
</div>
<div>
    <label for="image_path">Path gambar (contoh: assets/img/menu/kopi.jpg)</label>
    <input id="image_path" name="image_path" value="{{ old('image_path', $product->image_path ?? '') }}" maxlength="255">
    @error('image_path')
        <p role="alert">{{ $message }}</p>
    @enderror
</div>
<fieldset>
    <legend>Detail filter katalog (opsional)</legend>
    @foreach ([
        'type' => 'Type',
        'roast_level' => 'Roast Level',
        'taste_type' => 'Taste Type',
        'coffee_tastes_like' => 'Coffee Tastes Like',
        'region' => 'Region',
        'origin' => 'Origin',
        'process' => 'Process',
    ] as $key => $label)
        <div>
            <label for="catalog-attribute-{{ $key }}">{{ $label }}</label>
            <input
                id="catalog-attribute-{{ $key }}"
                name="catalog_attributes[{{ $key }}]"
                value="{{ old("catalog_attributes.{$key}", $product->catalog_attributes[$key] ?? '') }}"
            >
            @error("catalog_attributes.{$key}")
                <p role="alert">{{ $message }}</p>
            @enderror
        </div>
    @endforeach
</fieldset>
<div>
    <label for="is_available">Status</label>
    <select id="is_available" name="is_available" required>
        <option value="1" @selected((string) old('is_available', isset($product) ? (int) $product->is_available : 1) === '1')>Tersedia</option>
        <option value="0" @selected((string) old('is_available', isset($product) ? (int) $product->is_available : 1) === '0')>Tidak tersedia</option>
    </select>
    @error('is_available')
        <p role="alert">{{ $message }}</p>
    @enderror
</div>
<button type="submit">Simpan</button>
