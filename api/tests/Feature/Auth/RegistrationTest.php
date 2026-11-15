<?php

use App\Models\Role;
use App\Models\User;

function validRegistration(array $overrides = []): array
{
    return array_merge([
        'fname' => 'Test',
        'lname' => 'User',
        'phone' => '0712345678',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ], $overrides);
}

test('registration screen can be rendered', function () {
    $this->get('/register')
        ->assertOk()
        ->assertSee('name="fname"', false)
        ->assertDontSee('name="address"', false);
});

test('new users can register without an address', function () {
    $response = $this->post('/register', validRegistration());

    $response->assertSessionHasNoErrors();
    $this->assertAuthenticated();
    $response->assertRedirect(route('home', absolute: false));

    $user = User::firstWhere('email', 'test@example.com');
    expect($user->address)->toBeNull()
        ->and($user->is_admin)->toBeFalsy();
});

test('registration requires name, phone, email and password', function (string $field) {
    $this->post('/register', validRegistration([$field => '']))
        ->assertSessionHasErrors($field);

    $this->assertGuest();
})->with(['fname', 'lname', 'phone', 'email', 'password']);

test('phone and email must be unique', function (string $field) {
    $existing = User::factory()->create();

    $this->post('/register', validRegistration([$field => $existing->$field]))
        ->assertSessionHasErrors($field);
})->with(['phone', 'email']);

test('users cannot make themselves admin at sign-up', function () {
    $this->post('/register', validRegistration(['is_admin' => 1]));

    expect(User::firstWhere('email', 'test@example.com')->is_admin)->toBeFalsy();
});

test('users cannot assign themselves a role at sign-up', function () {
    $admin = Role::create(['name' => 'admin', 'guard_name' => 'web']);

    $this->post('/register', validRegistration(['role_id' => $admin->id]));

    expect(User::firstWhere('email', 'test@example.com')->roles)->toBeEmpty();
});
