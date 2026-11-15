<?php

use App\Actions\Orders\CancelOrder;
use App\Models\Basket;
use App\Models\Category;
use App\Models\DeliveryZone;
use App\Models\Offer;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Services\CartService;
use Illuminate\Testing\TestResponse;

function cartWith(array $lines): string
{
    $token = null;
    foreach ($lines as [$item, $qty]) {
        $key = $item instanceof Basket ? 'basket_id' : 'product_id';
        $token = test()->withHeaders($token ? [CartService::HEADER => $token] : [])
            ->postJson('/api/v1/cart/items', [$key => $item->id, 'quantity' => $qty])->json('data.token');
    }

    return $token;
}

function applyCode(string $token, string $code): TestResponse
{
    return test()->withHeader(CartService::HEADER, $token)->postJson('/api/v1/cart/discount', ['code' => $code]);
}

function checkoutWith(string $token, array $overrides = []): TestResponse
{
    return test()->withHeader(CartService::HEADER, $token)->postJson('/api/v1/orders', array_merge([
        'fname' => 'Jane', 'lname' => 'W', 'phone' => '0711000000', 'address' => '1 Rd', 'city' => 'Nairobi',
        'delivery_zone_id' => DeliveryZone::factory()->create(['delivery_fees' => 200])->id, 'accept_terms' => true,
    ], $overrides));
}

test('a percentage code discounts the whole cart, case-insensitively', function () {
    Offer::factory()->create(['code' => 'save10', 'value' => 10]);
    $token = cartWith([[Product::factory()->create(['price' => 500]), 2]]);

    applyCode($token, ' Save10 ')
        ->assertOk()
        ->assertJsonPath('data.discount.code', 'SAVE10')
        ->assertJsonPath('data.discount.amount', 100)
        ->assertJsonPath('data.subtotal', 1000)
        ->assertJsonPath('data.total', 900);
});

test('a fixed code never exceeds the eligible amount', function () {
    Offer::factory()->create(['code' => 'BIG', 'discount_type' => 'fixed', 'value' => 5000]);
    $token = cartWith([[Product::factory()->create(['price' => 300]), 1]]);

    applyCode($token, 'BIG')->assertJsonPath('data.discount.amount', 300)->assertJsonPath('data.total', 0);
});

test('category and product codes only discount matching items', function () {
    $fruit = Category::factory()->create();
    $mango = Product::factory()->for($fruit)->create(['price' => 200]);
    $flour = Product::factory()->create(['price' => 800]);
    Offer::factory()->create(['code' => 'FRUIT50', 'value' => 50, 'category_id' => $fruit->id]);
    Offer::factory()->create(['code' => 'FLOUR', 'discount_type' => 'fixed', 'value' => 100, 'product_id' => $flour->id]);
    $token = cartWith([[$mango, 2], [$flour, 1], [Basket::factory()->create(['price' => 1000]), 1]]);

    applyCode($token, 'FRUIT50')->assertJsonPath('data.discount.amount', 200);
    applyCode($token, 'FLOUR')->assertJsonPath('data.discount.amount', 100); // replaces the previous code
});

test('codes are refused when not usable', function (array $offer, string $message) {
    Offer::factory()->create(array_merge(['code' => 'NOPE'], $offer));
    $token = cartWith([[Product::factory()->create(['price' => 500]), 1]]);

    applyCode($token, 'NOPE')->assertUnprocessable()->assertJsonValidationErrors(['code' => $message]);
})->with([
    'inactive' => [['is_active' => false], 'This discount code is not valid.'],
    'not started' => [['start_at' => now()->addDay()], 'This discount code is not active yet.'],
    'expired' => [['end_at' => now()->subMinute()], 'This discount code has expired.'],
    'minimum order' => [['min_order_value' => 1000], 'This code needs an order of at least KES 1,000.'],
]);

test('an unknown code is refused', function () {
    $token = cartWith([[Product::factory()->create(), 1]]);

    applyCode($token, 'MADEUP')->assertJsonValidationErrors(['code' => 'This discount code is not valid.']);
});

test('a category code with no matching items is refused', function () {
    Offer::factory()->create(['code' => 'VEG', 'category_id' => Category::factory()->create()->id]);
    $token = cartWith([[Product::factory()->create(), 1]]);

    applyCode($token, 'VEG')->assertJsonValidationErrors(['code' => 'None of the items in your cart qualify for this code.']);
});

test('the discount can be removed', function () {
    Offer::factory()->create(['code' => 'SAVE10']);
    $token = cartWith([[Product::factory()->create(['price' => 100]), 1]]);
    applyCode($token, 'SAVE10');

    $this->withHeader(CartService::HEADER, $token)->deleteJson('/api/v1/cart/discount')
        ->assertOk()->assertJsonPath('data.discount', null)->assertJsonPath('data.total', 100);
});

test('checkout records the discount and the amount due', function () {
    $offer = Offer::factory()->create(['code' => 'SAVE10', 'value' => 10]);
    $token = cartWith([[Product::factory()->create(['price' => 1000]), 1]]);
    applyCode($token, 'SAVE10');

    checkoutWith($token)
        ->assertCreated()
        ->assertJsonPath('data.subtotal', 1000)
        ->assertJsonPath('data.discount', 100)
        ->assertJsonPath('data.discount_code', 'SAVE10')
        ->assertJsonPath('data.delivery_fee', 200)
        ->assertJsonPath('data.total', 1100);

    expect(Order::sole()->offer_id)->toBe($offer->id)->and(Order::sole()->amount_due)->toBe(1100.0);
});

test('a code that expired after being applied blocks checkout with a clear error', function () {
    $offer = Offer::factory()->create(['code' => 'SAVE10']);
    $token = cartWith([[Product::factory()->create(), 1]]);
    applyCode($token, 'SAVE10');
    $offer->update(['end_at' => now()->subMinute()]);

    $this->withHeader(CartService::HEADER, $token)->getJson('/api/v1/cart')
        ->assertJsonPath('data.discount.amount', 0)
        ->assertJsonPath('data.discount.error', 'This discount code has expired.');
    checkoutWith($token)->assertJsonValidationErrors(['discount_code' => 'This discount code has expired.']);

    expect(Order::count())->toBe(0);
});

test('total uses are limited, and canceled orders give their use back', function () {
    $offer = Offer::factory()->create(['code' => 'ONCE', 'max_uses' => 1]);
    $first = cartWith([[Product::factory()->create(), 1]]);
    applyCode($first, 'ONCE');
    checkoutWith($first)->assertCreated();

    $second = cartWith([[Product::factory()->create(), 1]]);
    applyCode($second, 'ONCE')->assertJsonValidationErrors(['code' => 'This discount code has been fully used.']);

    app(CancelOrder::class)(Order::sole());
    applyCode($second, 'ONCE')->assertOk();
});

test('per-customer limits use the account, or the phone number for guests', function () {
    Offer::factory()->create(['code' => 'WELCOME', 'max_uses_per_user' => 1]);

    $guest = cartWith([[Product::factory()->create(), 1]]);
    applyCode($guest, 'WELCOME');
    checkoutWith($guest, ['phone' => '0700111222'])->assertCreated();

    $again = cartWith([[Product::factory()->create(), 1]]);
    applyCode($again, 'WELCOME')->assertOk(); // unknown guest until checkout
    checkoutWith($again, ['phone' => '0700111222'])->assertJsonValidationErrors(['discount_code' => 'You have already used this discount code.']);

    $user = User::factory()->create();
    $userToken = $user->createToken('phone')->plainTextToken;
    $this->withToken($userToken)->postJson('/api/v1/cart/items', ['product_id' => Product::factory()->create()->id]);
    $this->withToken($userToken)->postJson('/api/v1/cart/discount', ['code' => 'WELCOME'])->assertOk();
});

test('an empty cart cannot take a code', function () {
    Offer::factory()->create(['code' => 'SAVE10']);

    $this->postJson('/api/v1/cart/discount', ['code' => 'SAVE10'])->assertJsonValidationErrors('code');
});
