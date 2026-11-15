<?php

use App\Models\Category;
use App\Models\Contact;
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
    'dashboard' => '/dashboard',
    'mass create' => '/masscreate',
    'customers' => '/users/customers',
    'users' => '/users',
    'create user' => '/users/create',
    'roles' => '/roles',
    'permissions' => '/permissions',
    'products' => '/products',
    'create product' => '/products/create',
    'categories' => '/categories',
    'baskets' => '/baskets',
    'estates' => '/estates',
    'delivery zones' => '/delivery_zones',
    'counties' => '/counties',
    'refs' => '/refs',
    'all orders' => '/orders',
    'all carts' => '/carts',
    'contacts' => '/contacts',
]);

test('guests are sent to login from admin pages', function (string $url) {
    $this->get($url)->assertRedirect(route('login'));
})->with('admin pages');

test('customers are forbidden from admin pages', function (string $url) {
    $this->actingAs(customer())->get($url)->assertForbidden();
})->with('admin pages');

test('admins can reach admin list pages', function (string $url) {
    $response = $this->actingAs(admin())->get($url);

    expect($response->status())->not->toBeIn([401, 403]);
})->with([
    '/dashboard', '/users', '/products', '/categories', '/baskets', '/orders', '/contacts',
]);

test('customers cannot create, edit or delete catalog records', function () {
    $product = Product::factory()->create();
    $category = Category::factory()->create();

    $this->actingAs(customer());
    $this->post('/products', ['name' => 'x'])->assertForbidden();
    $this->get("/products/{$product->id}/edit")->assertForbidden();
    $this->delete("/products/{$product->id}")->assertForbidden();
    $this->delete("/categories/{$category->id}")->assertForbidden();
    $this->post('/roles', ['name' => 'admin'])->assertForbidden();

    expect(Product::find($product->id))->not->toBeNull();
});

test('customers cannot read or edit contact submissions', function () {
    $contact = Contact::create(['name' => 'X', 'email' => 'x@example.com', 'phone' => '0700', 'source' => 'test']);

    $this->actingAs(customer());
    $this->get("/contacts/{$contact->id}")->assertForbidden();
    $this->delete("/contacts/{$contact->id}")->assertForbidden();
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

test('customers cannot dispatch orders', function () {
    $order = Order::factory()->create();

    $this->actingAs(customer())->get(route('orders.dispatch', $order))->assertForbidden();
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

    $this->withSession(['cart' => [$product->id => ['product_id' => $product->id, 'quantity' => 1]]])
        ->get(route('orders.create'))
        ->assertOk();
});
