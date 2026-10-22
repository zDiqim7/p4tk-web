<div>
    <label for="name">Nama</label>
    <input id="name" name="name" value="{{ old('name', $category->name ?? '') }}" required maxlength="255">
    @error('name')
        <p role="alert">{{ $message }}</p>
    @enderror
</div>
<div>
    <label for="slug">Slug</label>
    <input id="slug" name="slug" value="{{ old('slug', $category->slug ?? '') }}" required maxlength="255">
    @error('slug')
        <p role="alert">{{ $message }}</p>
    @enderror
</div>
<button type="submit">Simpan</button>
