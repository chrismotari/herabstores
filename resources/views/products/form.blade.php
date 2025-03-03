@csrf
<div class="container">
    <div class="mb-3">
        <label for="name" class="form-label">Product Name</label>
        <input type="text" class="form-control" placeholder="Product Name" aria-label="name"
            value="{{ old('name', $selected_product->name ?? '') }}" name="name">
        <x-input-error :messages="$errors->get('name')" class="mt-2 text-danger" />
    </div>

    <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $selected_product->description ?? '') }}</textarea>
        <x-input-error :messages="$errors->get('description')" />
    </div>

    <div class="mb-3">
        <label for="slug" class="form-label">Slug</label>
        <input type="text" class="form-control" id="slug" name="slug" value="{{ old('slug', $selected_product->slug ?? null) }}" required>
        <x-input-error :messages="$errors->get('slug')" />
    </div>

    <div class="mb-3">
        <label for="price" class="form-label">Price</label>
        <input type="number" class="form-control" id="price" name="price" step="0.01" value="{{ old('price', $selected_product->price ?? null) }}" required>
        <x-input-error :messages="$errors->get('price')" />
    </div>

    <div class="mb-3">
        <label for="stock" class="form-label">Stock</label>
        <input type="number" class="form-control" id="stock" name="stock" value="{{ old('stock', $selected_product->stock ?? null) }}" required>
        <x-input-error :messages="$errors->get('stock')" />
    </div>

    <div class="mb-3">
        <label for="image_url" class="form-label">Product Image</label>
        <input type="file" class="form-control" id="image_url" name="image_url">
        <x-input-error :messages="$errors->get('image_url')" />
    </div>

    <div class="mb-3">
        <label for="category_id" class="form-label">Category</label>
        <select class="form-control" id="category_id" name="category_id" required>
            @foreach($categories as $category)
            <option value="{{ $category->id }}" {{ old('category_id', $selected_product->category_id ?? null) == $category->id ? 'selected' : '' }}>
                {{ $category->name }}
            </option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('category_id')" />
    </div>

    <div class="mb-3 form-check">
        <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value=" {{ old('is_active', $selected_product->is_active ?? 0) }} ">
        <label class="form-check-label" for="is_active">Active</label>
    </div>

    <button type="submit" class="btn btn-primary">Submit</button>
</div>