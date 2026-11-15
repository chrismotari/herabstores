<?php

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
