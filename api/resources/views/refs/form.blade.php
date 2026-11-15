@csrf
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="mb-4">
                <label class="form-label">Referrer<span class="text-danger scale5 ms-2">*</span></label>
                <select class="nice-select default-select wide form-control" name="user_id">
                    <option value="" disabled {{ old('user_id', $selected_ref->user_id ?? null) === null ? 'selected' : '' }}>{{ old('user_id', $selected_ref->user_id ?? 'Choose...') }}</option>
                    @forelse($users as $user)
                    <option value="{{ $user->id }}" {{ old('user_id', $user->user_id ?? null) == $user->id ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                    @empty
                    <option disabled>No users available</option>
                    @endforelse
                </select>
                <x-input-error :messages="$errors->get('user_id')" class="mt-2 text-danger" />
            </div>
            <div class="mb-4">
                <label class="form-label">Description<span class="text-danger scale5 ms-2">*</span></label>
                <textarea class="form-control" placeholder="Description" aria-label="description" 
                    name="description">{{ old('description', $selected_ref->description ?? '') }}</textarea>
                <x-input-error :messages="$errors->get('description')" class="mt-2 text-danger" />
            </div>                             
            <div class="mb-4">
                <label class="form-label">Expiry Date<span class="text-danger scale3 ms-2">*</span></label>
                <div class="input-hasicon">
                    <input class="form-control bt-datepicker" value="{{ old('expires_at', $selected_ref->expires_at ?? '') }}" name="expires_at">
                    <div class="icon"><i class="far fa-calendar"></i></div>
                </div>
                <x-input-error :messages="$errors->get('expires_at')" class="mt-2 text-danger" />
            </div>
            <div class="mb-4">
                <label class="form-label">Status<span class="text-danger scale5 ms-2">*</span></label>
                <select class="nice-select default-select wide form-control" name="is_active">
                    <option value="1" @selected(old('is_active', $selected_ref->is_active ?? '') == '1')>Active</option>
                    <option value="0" @selected(old('is_active', $selected_ref->is_active ?? '') == '0')>Inactive</option>
                </select>
                <x-input-error :messages="$errors->get('status')" class="mt-2 text-danger" />
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