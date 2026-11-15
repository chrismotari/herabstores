{{-- Customer sign-up. Address and delivery location are collected at checkout. --}}
@php
    $fields = [
        ['fname', 'First Name', 'text', 'given-name'],
        ['lname', 'Last Name', 'text', 'family-name'],
        ['phone', 'Phone Number', 'tel', 'tel'],
        ['email', 'Email Address', 'email', 'email'],
    ];
@endphp
<div class="row">
    @foreach ($fields as [$name, $label, $type, $autocomplete])
        <div class="col-md-6 mb-3">
            <label class="form-label" for="{{ $name }}">{{ $label }}<span class="text-danger ms-1">*</span></label>
            <input id="{{ $name }}" type="{{ $type }}" class="form-control" name="{{ $name }}"
                value="{{ old($name) }}" autocomplete="{{ $autocomplete }}" required>
            <x-input-error :messages="$errors->get($name)" class="mt-2 text-danger" />
        </div>
    @endforeach
    <div class="col-md-6 mb-3">
        <label class="form-label" for="password">Password<span class="text-danger ms-1">*</span></label>
        <input id="password" type="password" class="form-control" name="password" autocomplete="new-password" required>
        <x-input-error :messages="$errors->get('password')" class="mt-2 text-danger" />
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label" for="password_confirmation">Confirm Password<span class="text-danger ms-1">*</span></label>
        <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" autocomplete="new-password" required>
    </div>
</div>
<div class="text-center">
    <button type="submit" class="btn btn-primary btn-block">Sign up</button>
</div>
