<?php

use App\Models\Country;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;

test('roles and permissions work with UUID keys', function () {
    $role = Role::create(['name' => 'shop-manager', 'guard_name' => 'web']);
    $permission = Permission::create(['name' => 'manage orders', 'guard_name' => 'web']);
    $role->givePermissionTo($permission);
    $user = User::factory()->create();

    $user->assignRole(Role::findById($role->id));

    expect($role->id)->toBeString()
        ->and($user->fresh()->hasRole('shop-manager'))->toBeTrue()
        ->and($user->fresh()->can('manage orders'))->toBeTrue();
});

test('admins can assign a role when creating a user', function () {
    $role = Role::create(['name' => 'courier', 'guard_name' => 'web']);
    $admin = User::factory()->create(['is_admin' => true]);

    $this->actingAs($admin)->post('/users', [
        'fname' => 'Otieno', 'lname' => 'K', 'phone' => '0722000000', 'email' => 'otieno@example.com',
        'password' => 'password', 'password_confirmation' => 'password',
        'address' => '1 Rd', 'city' => 'Kisumu', 'estate' => 'Milimani',
        'country_id' => Country::where('name', 'Kenya')->value('id'),
        'role_id' => $role->id,
    ])->assertSessionHasNoErrors();

    expect(User::firstWhere('email', 'otieno@example.com')->hasRole('courier'))->toBeTrue();
});
