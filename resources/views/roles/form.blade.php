<div class="card">
    @csrf
    <div class="card-body">
        <div class="row">
            

            <div class="col-md-6 mb-4">
                <label class="form-label">Name<span class="text-danger scale5 ms-2">*</span></label>
                <input type="text" class="form-control" placeholder="Role name" aria-label="name" value="{{ old('name') }}" name="name">
                <x-input-error :messages="$errors->get('name')" class="mt-2 text-danger" />
            </div>
            
        </div>
    </div>
    <div class="card-footer text-end">
        <div>
            <button type="cancel" class="btn btn-danger light me-3">Close</button>
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </div>
</div>
