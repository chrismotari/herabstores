<?php

use App\Models\Basket;
use App\Models\BasketProduct;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\County;
use App\Models\DeliveryZone;
use App\Models\Estate;
use App\Models\Favourite;
use App\Models\Order;
use App\Models\Product;

it('creates a valid model from each factory', function (string $model) {
    $record = $model::factory()->create();

    expect($record->exists)->toBeTrue()
        ->and($record->getKey())->toBeString();
})->with([
    Basket::class,
    BasketProduct::class,
    Cart::class,
    CartItem::class,
    Category::class,
    County::class,
    DeliveryZone::class,
    Estate::class,
    Favourite::class,
    Order::class,
    Product::class,
]);
