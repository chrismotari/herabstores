@csrf
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-xl-3 col-md-4 col-sm-6 mb-4">
                <label class="form-label">First Name<span class="text-danger scale5 ms-2">*</span></label>
                <input type="text" class="form-control" placeholder="First Name" aria-label="fname" value="{{ old('fname') }}" name="fname">
                <x-input-error :messages="$errors->get('fname')" class="mt-2 text-danger" />
            </div>
            <div class="col-xl-3 col-md-4 col-sm-6 mb-4">
                <label class="form-label">Middle Name</label>
                <input type="text" class="form-control" placeholder="Middle Name" aria-label="mname" value="{{ old('mname') }}" name="mname">
                <x-input-error :messages="$errors->get('mname')" class="mt-2 text-danger" />
            </div>

            <div class="col-xl-3 col-md-4 col-sm-6 mb-4">
                <label class="form-label">Last Name<span class="text-danger scale5 ms-2">*</span></label>
                <input type="text" class="form-control" placeholder="Last Name" aria-label="lname" value="{{ old('lname') }}" name="lname">
                <x-input-error :messages="$errors->get('lname')" class="mt-2 text-danger" />
            </div>
            <div class="col-xl-3 col-md-4 col-sm-6 mb-4">
                <label class="form-label">Phone Number<span class="text-danger scale5 ms-2">*</span></label>
                <input type="text" class="form-control" placeholder="Phone" aria-label="phone" value="{{ old('phone') }}" name="phone">
                <x-input-error :messages="$errors->get('phone')" class="mt-2 text-danger" />
            </div>
            <div class="col-xl-3 col-md-4 col-sm-6 mb-4">
                <label class="form-label">Alternative Number </label>
                <input type="text" class="form-control" placeholder="Alt Phone" aria-label="alt_phone" value="{{ old('alt_phone') }}" name="alt_phone">
                <x-input-error :messages="$errors->get('alt_phone')" class="mt-2 text-danger" />
            </div>
            <div class="col-xl-3 col-md-4 col-sm-6 mb-4">
                <label class="form-label">Email Address<span class="text-danger scale5 ms-2">*</span></label>
                <input type="text" class="form-control" placeholder="Email" aria-label="email" value="{{ old('email') }}" name="email">
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-danger" />
            </div>
            <div class="col-xl-3 col-md-4 col-sm-6 mb-4">
                <label class="form-label">Alternative Email Address </label>
                <input type="text" class="form-control" placeholder="Alt Email" aria-label="alt_email" value="{{ old('alt_email') }}" name="alt_email">
                <x-input-error :messages="$errors->get('alt_email')" class="mt-2 text-danger" />
            </div>
            <div class="col-xl-3 col-md-4 col-sm-6 mb-4">
                <label class="form-label">Address<span class="text-danger scale5 ms-2">*</span></label>
                <input type="text" class="form-control" placeholder="Address" aria-label="address" value="{{ old('address') }}" name="address">
                <x-input-error :messages="$errors->get('address')" class="mt-2 text-danger" />
            </div>
            <div class="col-xl-3 col-md-4 col-sm-6 mb-4">
                <label class="form-label">City<span class="text-danger scale5 ms-2">*</span></label>
                <input type="text" class="form-control" placeholder="City" aria-label="city" value="{{ old('city') }}" name="city">
                <x-input-error :messages="$errors->get('city')" class="mt-2 text-danger" />
            </div>

            <div class="col-xl-3 col-md-4 col-sm-6 mb-4">
                <label class="form-label">State</label>
                <select class="nice-select default-select wide form-control" name="state_id">
                    <option value="" disabled {{ old('state_id') === null ? 'selected' : '' }}>Choose...</option>
                    @forelse($states as $state)
                    <option value="{{ $state->id }}" {{ old('state_id') == $state->id ? 'selected' : '' }}>
                        {{ $state->name }}
                    </option>
                    @empty
                    <option disabled>No states available</option>
                    @endforelse
                </select>
                <x-input-error :messages="$errors->get('state_id')" class="mt-2 text-danger" />
            </div>


            <div class="col-xl-3 col-md-4 col-sm-6 mb-4">
                <label class="form-label">Zip Code<span class="text-danger scale5 ms-2">*</span></label>
                <input type="text" class="form-control" placeholder="Zip Code" aria-label="zip_code" value="{{ old('zip_code') }}" name="zip_code">
                <x-input-error :messages="$errors->get('zip_code')" class="mt-2 text-danger" />
            </div>

            <div class="col-xl-3 col-md-4 col-sm-6 mb-4">
                <label class="form-label">Country<span class="text-danger scale5 ms-2">*</span></label>
                <select class="nice-select default-select wide form-control" name="country_id">
                    <option value="" disabled {{ old('country_id') === null ? 'selected' : '' }}>Choose...</option>
                    @forelse($countries as $country)
                    <option value="{{ $country->id }}" {{ old('country_id') == $country->id ? 'selected' : '' }}>
                        {{ $country->name }}
                    </option>
                    @empty
                    <option disabled>No countries available</option>
                    @endforelse
                </select>
                <x-input-error :messages="$errors->get('country_id')" class="mt-2 text-danger" />
            </div>


            <div class="col-xl-3 col-md-4 col-sm-6 mb-4">
                <label class="form-label">Profile Photo </label>
                <input type="file" class="form-control" placeholder="Profile Photo" aria-label="photo_url" value="{{ old('photo_url') }}" name="photo_url">
                <x-input-error :messages="$errors->get('photo_url')" class="mt-2 text-danger" />
            </div>

            <div class="col-xl-3 col-md-4 col-sm-6 mb-4">
                <label class="form-label">Password<span class="text-danger scale5 ms-2">*</span></label>
                <input type="password" class="form-control" placeholder="Password" aria-label="password" name="password">
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-danger" />
            </div>
            <div class="col-xl-3 col-md-4 col-sm-6 mb-4">
                <label class="form-label">Confirm Password<span class="text-danger scale5 ms-2">*</span></label>
                <input type="password" class="form-control" placeholder="Confirm Password" aria-label="confirm_password" name="password_confirmation">
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-danger" />
            </div>

            <div class="col-xl-3 col-md-4 col-sm-6 mb-4">
                <label class="form-label">Role<span class="text-danger scale5 ms-2">*</span></label>
                <select class="nice-select default-select wide form-control" name="role_id">
                    <option value="" disabled {{ old('role_id') === null ? 'selected' : '' }}>Choose...</option>
                    @forelse($roles as $role)
                    <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                        {{ $role->name }}
                    </option>
                    @empty
                    <option disabled>No roles available</option>
                    @endforelse
                </select>
                <x-input-error :messages="$errors->get('role_id')" class="mt-2 text-danger" />
            </div>


            <div class="col-xl-3 col-md-4 col-sm-6 mb-4">
                <label class="form-label">Date of Birth<span class="text-danger scale3 ms-2">*</span></label>
                <div class="input-hasicon">
                    <input class="form-control bt-datepicker" value="{{ old('dob') }}" name="dob">
                    <div class="icon"><i class="far fa-calendar"></i></div>
                </div>
                <x-input-error :messages="$errors->get('dob')" class="mt-2 text-danger" />
            </div>


            <div class="col-xl-3 col-md-4 col-sm-6 mb-4">
                <label class="form-label">Select Gender:<span class="text-danger scale5 ms-2">*</span></label>
                <select class="nice-select default-select wide form-control" name="gender">
                    <option value="" disabled {{ old('gender') === null ? 'selected' : '' }}>Choose...</option>
                    <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                    <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                </select>
                <x-input-error :messages="$errors->get('gender')" class="mt-2 text-danger" />
            </div>

            <div class="col-xl-3 col-md-4 col-sm-6 mb-4">
                <label class="form-label">Status<span class="text-danger scale5 ms-2">*</span></label>
                <select class="nice-select default-select wide form-control" name="status">
                    <option value="" disabled {{ old('status') === null ? 'selected' : '' }}>Choose...</option>
                    <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Inactive</option>
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