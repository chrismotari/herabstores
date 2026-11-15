<?php

use App\Models\User;

test('non-uuid ids in URLs are a 404, not a server error', function (string $url) {
    $this->actingAs(User::factory()->create(['is_admin' => true]))
        ->get($url)
        ->assertNotFound();
})->with(['/products/not-a-uuid', '/orders/not-a-uuid', '/users/not-a-uuid', '/categories/not-a-uuid']);

test('non-uuid product ids fail validation instead of erroring', function () {
    $this->actingAs(User::factory()->create())
        ->post(route('favourites.store'), ['product_id' => 'nope'])
        ->assertRedirect();

    $this->post(route('cart_items.store'), ['product_id' => 'nope', 'quantity' => 1])
        ->assertSessionHasErrors('product_id');
});
