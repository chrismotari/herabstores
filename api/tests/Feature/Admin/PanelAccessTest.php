<?php

use App\Models\User;

test('guests are sent to the admin login', function () {
    $this->get('/admin')->assertRedirect('/admin/login');
});

test('active admins can open the panel', function () {
    $this->actingAs(User::factory()->create(['is_admin' => true]))->get('/admin')->assertOk();
});

test('customers and deactivated admins are refused', function (array $state) {
    $this->actingAs(User::factory()->create($state))->get('/admin')->assertForbidden();
})->with([
    'customer' => [['is_admin' => false]],
    'inactive admin' => [['is_admin' => true, 'is_active' => false]],
]);

test('the admin login page renders', function () {
    $this->get('/admin/login')->assertOk();
});
