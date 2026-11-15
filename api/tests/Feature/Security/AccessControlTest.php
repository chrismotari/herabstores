<?php

use App\Models\Order;
use App\Models\Product;
use App\Models\User;

function customer(): User
{
    return User::factory()->create(['is_admin' => false]);
}

function admin(): User
{
    return User::factory()->create(['is_admin' => true]);
}

dataset('admin pages', [
    'dashboard' => '/admin',
    'products' => '/admin/products',
    'create product' => '/admin/products/create',
    'categories' => '/admin/categories',
    'baskets' => '/admin/baskets',
    'orders' => '/admin/orders',
    'customers' => '/admin/customers',
    'staff' => '/admin/staff',
    'roles' => '/admin/roles',
    'delivery zones' => '/admin/delivery-zones',
    'contacts' => '/admin/contacts',
]);

test('guests are sent to the admin login', function (string $url) {
    $this->get($url)->assertRedirect('/admin/login');
})->with('admin pages');

test('customers are forbidden from admin pages', function (string $url) {
    $this->actingAs(customer())->get($url)->assertForbidden();
})->with('admin pages');

test('admins can reach admin pages', function (string $url) {
    $this->actingAs(admin())->get($url)->assertOk();
})->with('admin pages');

test('the old Blade admin is gone', function (string $url) {
    // 405 where only a storefront POST remains at that URL (orders, contacts)
    expect($this->actingAs(admin())->get($url)->status())->toBeIn([404, 405]);
})->with(['/users', '/users/create', '/roles', '/permissions', '/products/create', '/categories', '/baskets', '/estates', '/delivery_zones', '/counties', '/refs', '/orders', '/carts', '/contacts', '/masscreate']);

test('/dashboard now redirects to the Filament admin', function () {
    $this->actingAs(admin())->get('/dashboard')->assertRedirect('/admin');
});

test('anyone can submit the contact form', function () {
    $response = $this->post('/contacts', ['name' => 'Jane', 'email' => 'jane@example.com', 'message' => 'Hi']);

    expect($response->status())->not->toBeIn([401, 403, 404, 405]);
});

test('customers can view only their own orders', function () {
    $me = customer();
    $mine = Order::factory()->for($me)->create();
    $theirs = Order::factory()->create();

    $this->actingAs($me);
    $this->get(route('orders.show', $mine))->assertOk();
    $this->get(route('orders.show', $theirs))->assertForbidden();
    $this->get(route('orders.track', $theirs))->assertForbidden();
});

test('guests cannot view orders', function () {
    $order = Order::factory()->create();

    $this->get(route('orders.show', $order))->assertRedirect(route('login'));
});

test('admins can view any order', function () {
    $order = Order::factory()->create();

    $this->actingAs(admin())->get(route('orders.show', $order))->assertOk();
});

test('customers can view only their own profile', function () {
    $me = customer();
    $other = customer();

    $this->actingAs($me);
    $this->get(route('users.show', $me))->assertOk();
    $this->get(route('users.show', $other))->assertForbidden();
    $this->patch(route('users.update', $other), ['fname' => 'Hacked'])->assertForbidden();

    expect($other->fresh()->fname)->not->toBe('Hacked');
});

test('guest checkout page is still public', function () {
    $product = Product::factory()->create();

    $this->withSession(guestSessionWith($product))
        ->get(route('orders.create'))
        ->assertOk();
});
