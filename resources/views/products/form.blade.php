
<div class="container">
    <h2>Create Product</h2>
    <div class="mb-3">
            <label for="name" class="form-label">Product Name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
            <x-input-error :messages="$errors->first('name')" />
        </div>
        
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description" rows="3">{{ old('description') }}</textarea>
            <x-input-error :messages="$errors->first('description')" />
        </div>
        
        <div class="mb-3">
            <label for="slug" class="form-label">Slug</label>
            <input type="text" class="form-control" id="slug" name="slug" value="{{ old('slug') }}" required>
            <x-input-error :messages="$errors->first('slug')" />
        </div>
        
        <div class="mb-3">
            <label for="price" class="form-label">Price</label>
            <input type="number" class="form-control" id="price" name="price" step="0.01" value="{{ old('price') }}" required>
            <x-input-error :messages="$errors->first('price')" />
        </div>
        
        <div class="mb-3">
            <label for="stock" class="form-label">Stock</label>
            <input type="number" class="form-control" id="stock" name="stock" value="{{ old('stock') }}" required>
            <x-input-error :messages="$errors->first('stock')" />
        </div>
        
        <div class="mb-3">
            <label for="image_url" class="form-label">Product Image</label>
            <input type="file" class="form-control" id="image_url" name="image_url">
            <x-input-error :messages="$errors->first('image_url')" />
        </div>
        
        <div class="mb-3">
            <label for="category_id" class="form-label">Category</label>
            <select class="form-control" id="category_id" name="category_id" required>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->first('category_id')" />
        </div>
        
        <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" old('is_active', true) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_active">Active</label>
        </div>
        
        <button type="submit" class="btn btn-primary">Create Product</button>
</div>
