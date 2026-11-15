<?php

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

test('the old Blade admin and storefront are gone', function (string $url) {
    expect($this->actingAs(admin())->get($url)->status())->toBe(404);
})->with([
    // Blade admin
    '/users', '/users/create', '/roles', '/permissions', '/products/create', '/categories', '/baskets',
    '/estates', '/delivery_zones', '/counties', '/refs', '/carts', '/contacts', '/masscreate',
    // Blade storefront (now the Next.js app, which 301s the old URLs)
    '/shop', '/products/apples', '/orders/create', '/carts/show', '/profile', '/login', '/register',
    '/favourites', '/about-us', '/terms-and-conditions',
]);

test('/dashboard now redirects to the Filament admin', function () {
    $this->actingAs(admin())->get('/dashboard')->assertRedirect('/admin');
});

test('the API domain root sends people to the shop', function () {
    config(['services.storefront.url' => 'https://shop.example']);

    $this->get('/')->assertRedirect('https://shop.example');
});

test('the API domain is not indexed', function () {
    expect(file_get_contents(public_path('robots.txt')))->toContain('Disallow: /');
});
