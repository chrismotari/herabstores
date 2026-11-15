<?php

use App\Http\Controllers\Api\V1\AccountController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\BasketController;
use App\Http\Controllers\Api\V1\CartController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\CheckoutController;
use App\Http\Controllers\Api\V1\ContactController;
use App\Http\Controllers\Api\V1\ProductController;
use Illuminate\Support\Facades\Route;

/*
| REST API for the Next.js storefront and the React Native app.
| Everything is versioned under /api/v1 and named api.v1.*.
| Controllers live in App\Http\Controllers\Api\V1.
| Auth: Bearer token (mobile) or Sanctum session cookie (Next.js).
*/

Route::prefix('v1')->name('api.v1.')->group(function () {
    Route::get('/', fn () => ['name' => config('app.name'), 'version' => 'v1'])->name('index');

    // Catalog (public, cacheable)
    Route::middleware('cache.headers:public;max_age=60;s_maxage=300;etag')->group(function () {
        Route::get('categories', [CategoryController::class, 'index'])->name('categories.index');
        Route::get('categories/{slug}', [CategoryController::class, 'show'])->name('categories.show');
        Route::get('products', [ProductController::class, 'index'])->middleware('throttle:search')->name('products.index');
        Route::get('products/{slug}', [ProductController::class, 'show'])->name('products.show');
        Route::get('baskets', [BasketController::class, 'index'])->name('baskets.index');
        Route::get('baskets/{slug}', [BasketController::class, 'show'])->name('baskets.show');
    });

    // Cart: logged-in user's cart, or a guest cart via the X-Cart-Token header
    Route::prefix('cart')->name('cart.')->controller(CartController::class)->group(function () {
        Route::get('/', 'show')->name('show');
        Route::post('/', 'store')->name('store');
        Route::delete('/', 'clear')->name('clear');
        Route::post('items', 'addItem')->name('items.store');
        Route::patch('items/{item}', 'updateItem')->whereUuid('item')->name('items.update');
        Route::delete('items/{item}', 'removeItem')->whereUuid('item')->name('items.destroy');
    });

    // Checkout (pay on delivery)
    Route::get('delivery-zones', [CheckoutController::class, 'deliveryZones'])
        ->middleware('cache.headers:public;max_age=300;etag')->name('delivery-zones.index');
    Route::post('orders', [CheckoutController::class, 'placeOrder'])->middleware('throttle:10,1')->name('orders.store');

    Route::post('contacts', [ContactController::class, 'store'])->middleware('throttle:5,1')->name('contacts.store');

    // Auth
    Route::prefix('auth')->name('auth.')->controller(AuthController::class)->group(function () {
        Route::middleware('throttle:auth')->group(function () {
            Route::post('register', 'register')->name('register');
            Route::post('login', 'login')->name('login');
            Route::post('forgot-password', 'forgotPassword')->name('forgot-password');
            Route::post('reset-password', 'resetPassword')->name('reset-password');
        });

        Route::middleware('auth:sanctum')->group(function () {
            Route::post('logout', 'logout')->name('logout');
            Route::post('email/verification-notification', 'sendVerificationEmail')
                ->middleware('throttle:6,1')->name('verification.send');
        });
    });

    // The signed-in customer's account
    Route::middleware('auth:sanctum')->prefix('me')->name('me.')->group(function () {
        Route::get('/', [AuthController::class, 'me'])->name('show');
        Route::controller(AccountController::class)->group(function () {
            Route::patch('/', 'update')->name('update');
            Route::delete('/', 'destroy')->name('destroy');
            Route::put('password', 'updatePassword')->name('password');
            Route::get('orders', 'orders')->name('orders.index');
            Route::get('orders/{order}', 'order')->name('orders.show');
            Route::get('favourites', 'favourites')->name('favourites.index');
            Route::post('favourites', 'addFavourite')->name('favourites.store');
            Route::delete('favourites/{product}', 'removeFavourite')->whereUuid('product')->name('favourites.destroy');
        });
    });
});
