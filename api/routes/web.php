<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CartItemController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\FavouriteController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Models\Product;
use Illuminate\Support\Facades\Route;

/*
| Access levels
|   public:   storefront, cart, guest checkout, contact form
|   auth:     customer's own profile, favourites, orders (scoped by policies)
|   admin:    everything under the backend layout
*/

// The admin lives in Filament at /admin. Keep the old name for existing links.
Route::redirect('dashboard', '/admin')->name('dashboard');

// Public: cart and guest checkout (before the auth group so orders/create isn't read as orders/{order})
Route::get('orders/create', [OrderController::class, 'create'])->name('orders.create');
Route::post('orders', [OrderController::class, 'store'])->name('orders.store');
Route::post('cart_items', [CartItemController::class, 'store'])->name('cart_items.store');
Route::delete('/cart-items/{item}', [CartItemController::class, 'destroy'])->whereUuid('item')->name('cart_items.remove');
Route::get('/carts/show/{cart?}', [CartController::class, 'show'])->name('carts.show');
Route::post('contacts', [ContactController::class, 'store'])->name('contacts.store');

// Logged-in customers (record-level access via policies)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('users/{user}', [UserController::class, 'show'])->name('users.show')->can('view', 'user');
    Route::match(['put', 'patch'], 'users/{user}', [UserController::class, 'update'])->name('users.update')->can('update', 'user');

    Route::get('orders/{order}', [OrderController::class, 'show'])->name('orders.show')->can('view', 'order');
    Route::get('orders/track/{order}', [OrderController::class, 'track'])->name('orders.track')->can('view', 'order');

    Route::resource('favourites', FavouriteController::class);
    Route::delete('products/{product}/unfavourite', [ProductController::class, 'unfavourite'])->name('products.unfavourite');

    Route::get('/home', function () {
        return redirect('/');
    })->name('home');
});

// Must come before products/{product?}, or "search" is treated as a product id.
Route::post('products/search', [ProductController::class, 'search'])->name('products.search');
Route::get('products/search', [ProductController::class, 'search']);
Route::get('/products/{product?}', [ProductController::class, 'show'])->name('products.show');

Route::get('shop', function () {
    return view('shop')->with('products', Product::all());
})->name('shop');

Route::get('/index', function () {
    return redirect('/');
})->name('index');

Route::get('/', function () {
    return view('index')->with('products', Product::all());
});

Route::get('/terms-and-conditions', function () {
    return view('terms-and-conditions');
})->name('terms.and.conditions');

Route::get('/about-us', function () {
    return view('about-us');
})->name('about.us');

Route::get('/contact-us', function () {
    return view('contact-us');
})->name('contact.us');

Route::get('/faq', function () {
    return view('contact-us');
})->name('faq');

require __DIR__.'/auth.php';
