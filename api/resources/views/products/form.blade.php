@csrf
<div class="container row">
    <div class="mb-3 col-md-6">
        <label for="name" class="form-label">Product Name</label>
        <input type="text" class="form-control" placeholder="Product Name" aria-label="name"
            value="{{ old('name', $selected_product->name ?? '') }}" name="name" required>
        <x-input-error :messages="$errors->get('name')" class="mt-2 text-danger" />
    </div>

    <div class="mb-3 col-md-6">
        <label for="slug" class="form-label">Slug</label>
        <input type="text" class="form-control" id="slug" name="slug" value="{{ old('slug', $selected_product->slug ?? null) }}" required>
        <x-input-error :messages="$errors->get('slug')" />
    </div>

    <div class="mb-3 col-md-6">
        <label for="price" class="form-label">Price</label>
        <input type="number" class="form-control" id="price" name="price" step="0.01" value="{{ old('price', $selected_product->price ?? null) }}" required>
        <x-input-error :messages="$errors->get('price')" class="mt-2 text-danger" />
    </div>

    <div class="mb-3 col-md-6">
        <label for="stock" class="form-label">Stock</label>
        <input type="number" class="form-control" id="stock" name="stock" value="{{ old('stock', $selected_product->stock ?? null) }}" required>
        <x-input-error :messages="$errors->get('stock')" />
    </div>

    <div class="mb-3 col-md-6">
        <label for="category_id" class="form-label">Category</label>
        <select class="form-control" id="category_id" name="category_id" required>
            @foreach($categories as $category)
            <option value="{{ $category->id }}" {{ old('category_id', $selected_product->category_id ?? null) == $category->id ? 'selected' : '' }}>
                {{ $category->name }}
            </option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('category_id')" class="mt-2 text-danger" />
    </div>
    <div class="mb-3 col-md-6">
        <label for="is_active" class="form-label">Active</label>
        <select class="form-control" id="is_active" name="is_active" required>
            <option value="1" {{ old('is_active', $selected_product->is_active ?? '') == 1 ? 'selected' : "" }}>Activate</option>
            <option value="0" {{ old('is_active', $selected_product->is_active ?? '') == 0 ? 'selected' : ''  }}>Hide</option>
            <option  value=""> Select </option>

            

            
        </select>
        <x-input-error :messages="$errors->get('is_active')" class="mt-2 text-danger" />
    </div>

    <div class="mb-3 col-md-12">
        <label for="description" class="form-label">Description</label>
        <textarea class="form-control" id="description" name="description" rows="5" required>{{ old('description', $selected_product->description ?? '') }}</textarea>
        <x-input-error :messages="$errors->get('description')" class="mt-2 text-danger" />
    </div>

    @if(!empty($selected_product) && $selected_product->images && $selected_product->images->count())
    <div class="mb-3">
        <label class="form-label">Existing Product Images</label>
        <div class="d-flex flex-wrap gap-2">
            @foreach($selected_product->images as $img)
            <div class="card" style="width:150px; margin-right:8px;">
                <img src="{{ asset('storage/' . $img->image_url) }}" class="card-img-top" alt="" style="height:100px; object-fit:cover;">
                <div class="card-body p-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="primary_image" id="primary_{{ $img->id }}" value="{{ $img->id }}" {{ (old('primary_image', $selected_product->image_url) == $img->image_url) ? 'checked' : '' }}>
                        <label class="form-check-label" for="primary_{{ $img->id }}">Primary</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remove_images[]" id="remove_{{ $img->id }}" value="{{ $img->id }}">
                        <label class="form-check-label text-danger" for="remove_{{ $img->id }}">Remove</label>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- <div class="mb-3 col-md-6">
        <label for="image_url" class="form-label">Primary Image (optional)</label>
        <input type="file" class="form-control" id="image_url" name="image_url">
        <x-input-error :messages="$errors->get('image_url')" />
    </div> --}}

    <div class="mb-3 col-md-6">
        <label for="images" class="form-label">Upload More Product Images</label>
        <input type="file" class="form-control" id="images" name="images[]" multiple>
        <x-input-error :messages="$errors->get('images.*')" class="mt-2 text-danger" />
    </div>

    {{-- <div class="mb-3 form-check">
        <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value=" {{ old('is_active', $selected_product->is_active ?? '') }} ">
        <label class="form-check-label" for="is_active">Active</label>
    </div> --}}

    <button type="submit" class="btn btn-primary" >Submit</button>
</div>