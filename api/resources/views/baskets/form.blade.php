@csrf
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="mb-4 col-md-6">
                <label class="form-label">Name<span class="text-danger scale5 ms-2">*</span></label>
                <input type="text" class="form-control" placeholder="Name" aria-label="name"
                    value="{{ old('name', $selected_basket->name ?? '') }}" name="name">
                <x-input-error :messages="$errors->get('name')" class="mt-2 text-danger" />
            </div>
            {{-- {{ $selected_basket->effective_from }} --}}
            <div class="mb-4 col-md-6">
                <label class="form-label">Slug<span class="text-danger scale5 ms-2">*</span></label>
                <input type="text" class="form-control" placeholder="Slug" aria-label="slug"
                    value="{{ old('slug', $selected_basket->slug ?? '') }}" name="slug">
                <x-input-error :messages="$errors->get('slug')" class="mt-2 text-danger" />
            </div>
            <div class="mb-4 col-md-6">
                <label class="form-label">Price<span class="text-danger scale5 ms-2">*</span></label>
                <input type="number" class="form-control" placeholder="Price" aria-label="price"
                    value="{{ old('price', $selected_basket->price ?? '') }}" name="price">
                <x-input-error :messages="$errors->get('price')" class="mt-2 text-danger" />
            </div>
            <div class="mb-4 col-md-6">
                <label class="form-label">Stock<span class="text-danger scale5 ms-2">*</span></label>
                <input type="number" class="form-control" placeholder="Stock" aria-label="stock"
                    value="{{ old('stock', $selected_basket->stock ?? '') }}" name="stock">
                <x-input-error :messages="$errors->get('stock')" class="mt-2 text-danger" />
            </div>
            <div class="mb-4 col-md-6">
                <label class="form-label">Effective From<span class="text-danger scale5 ms-2">*</span></label>
                <input type="date" class="form-control" placeholder="Effective From" aria-label="effective_from"
                    value="{{ old('effective_from', isset($selected_basket) ? $selected_basket->effective_from?->format('Y-m-d') : '') }}" name="effective_from">
                <x-input-error :messages="$errors->get('effective_from')" class="mt-2 text-danger" />
            </div>
            <div class="mb-4 col-md-6">
                <label class="form-label">Effective To<span class="text-danger scale5 ms-2">*</span></label>
                <input type="date" class="form-control" placeholder="Effective To" aria-label="effective_to"
                value="{{ old('effective_to', isset($selected_basket) ? $selected_basket->effective_to?->format('Y-m-d') : '') }}" name="effective_to">
                <x-input-error :messages="$errors->get('effective_to')" class="mt-2 text-danger" />
            </div>
            <div class="mb-4 col-md-6">
                <label class="form-label">Status<span class="text-danger scale5 ms-2">*</span></label>
                <select class="nice-select default-select wide form-control" name="is_active">
                    <option value="1" @selected(old('is_active', $selected_basket->is_active ?? '') == '1')>Active</option>
                    <option value="0" @selected(old('is_active', $selected_basket->is_active ?? '') == '0')>Inactive</option>
                </select>
                <x-input-error :messages="$errors->get('is_active')" class="mt-2 text-danger" />
            </div>
            <div class="mb-4 col-md-6">
                <label class="form-label">Cover Image</label>
                <input type="file" class="form-control" placeholder="Cover Image" aria-label="image" name="image">
                <x-input-error :messages="$errors->get('image')" class="mt-2 text-danger" />
            </div>
            <div class="mb-4">
                <label class="form-label">Description<span class="text-danger scale5 ms-2">*</span></label>
                <textarea class="form-control" placeholder="Description" aria-label="description" rows="3"
                    name="description">{{ old('description', $selected_basket->description ?? '') }}</textarea>
                <x-input-error :messages="$errors->get('description')" class="mt-2 text-danger" />
            </div>
        </div>

    </div>
    <div class="card-footer text-end">
        <div>
            <a href="javascript:void(0);" class="btn btn-danger light me-3">Close</a>
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </div>
</div>