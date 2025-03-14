@csrf
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="mb-4">
                <label class="form-label">Name<span class="text-danger scale5 ms-2">*</span></label>
                <input type="text" class="form-control" placeholder="Name" aria-label="name" 
                    value="{{ old('name', $selected_category->name ?? '') }}" name="name">
                <x-input-error :messages="$errors->get('name')" class="mt-2 text-danger" />
            </div>
            <div class="mb-4">
                <label class="form-label">Description<span class="text-danger scale5 ms-2">*</span></label>
                <textarea class="form-control" placeholder="Description" aria-label="description" 
                    name="description">{{ old('description', $selected_category->description ?? '') }}</textarea>
                <x-input-error :messages="$errors->get('description')" class="mt-2 text-danger" />
            </div>          
                       
            <div class="mb-4">
                <label class="form-label">Cover Image</label>
                <input type="file" class="form-control" placeholder="Cover Image" aria-label="image_url" name="image_url">
                <x-input-error :messages="$errors->get('image_url')" class="mt-2 text-danger" />
            </div>                     
                      
            <div class="mb-4">
                <label class="form-label">Status<span class="text-danger scale5 ms-2">*</span></label>
                <select class="nice-select default-select wide form-control" name="is_active">
                    <option value="1" @selected(old('is_active', $selected_category->is_active ?? '') == '1')>Active</option>
                    <option value="0" @selected(old('is_active', $selected_category->is_active ?? '') == '0')>Inactive</option>
                </select>
                <x-input-error :messages="$errors->get('is_active')" class="mt-2 text-danger" />
            </div>
        </div>

    </div>
    <div class="card-footer text-end">
        <div>
            <a href="javascript:void(0);" class="btn btn-danger light me-3">Close</a>
            <button type="submit" class="btn btn-primary">Submit</a>
        </div>
    </div>
</div>