<?php

use App\Http\Controllers\BasketController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CartItemController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CountyController;
use App\Http\Controllers\DeliveryZoneController;
use App\Http\Controllers\EstateController;
use App\Http\Controllers\FavouriteController;
use App\Http\Controllers\MassAddController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RefController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Models\Product;
use Illuminate\Support\Facades\Route;

/*
| Access levels
|   public:   storefront, cart, guest checkout, contact form
|   auth:     customer's own profile, favourites, orders (scoped by policies)
|   admin:    everything under the backend layout
*/

// Admin only
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    Route::get('/admin', function () {
        return redirect('/dashboard');
    })->name('admin');

    Route::get('/masscreate', [MassAddController::class, 'massAddProducts'])->name('masscreate');
    Route::get('/products/massadd', [MassAddController::class, 'massAddProducts'])->name('products.massadd');
    Route::get('/users/customers', [UserController::class, 'customers'])->name('users.customers');

    Route::post('orders/dispatch', [OrderController::class, 'dispatch'])->name('orders.dispatch.save');
    Route::get('orders/dispatch/{order}', [OrderController::class, 'dispatch'])->name('orders.dispatch');
    Route::resource('orders', OrderController::class)->only(['index', 'edit', 'update', 'destroy']);

    Route::resource('users', UserController::class)->except(['show', 'update']);
    Route::resource('roles', RoleController::class);
    Route::resource('permissions', PermissionController::class);
    Route::resource('products', ProductController::class)->except(['show']);
    Route::resource('categories', CategoryController::class);
    Route::resource('baskets', BasketController::class);
    Route::resource('estates', EstateController::class);
    Route::resource('delivery_zones', DeliveryZoneController::class);
    Route::resource('counties', CountyController::class);
    Route::resource('refs', RefController::class);
    Route::resource('carts', CartController::class)->except(['show']);
    Route::resource('contacts', ContactController::class)->except(['store']);
});

// Public: cart and guest checkout (before the auth group so orders/create isn't read as orders/{order})
Route::get('orders/create', [OrderController::class, 'create'])->name('orders.create');
Route::post('orders', [OrderController::class, 'store'])->name('orders.store');
Route::post('cart_items', [CartItemController::class, 'store'])->name('cart_items.store');
Route::delete('/cart-items/remove/{product}', [CartItemController::class, 'destroy'])->name('cart_items.destroy');
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
