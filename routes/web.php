<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CartItemController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\EstateController;
use App\Http\Controllers\FavouriteController;
use App\Http\Controllers\MassAddController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RefController;
use App\Http\Controllers\RoleController;
use App\Models\Product;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('dashboard', function (){
        return view('dashboard');
    })->name('dashboard')->middleware('admin');

    Route::get('/masscreate', [MassAddController::class, 'massAddProducts'])->name('masscreate')->middleware('admin');

    Route::get('/admin', function () {
        return redirect('/dashboard'); 
    })->name('admin');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/users/customers', [UserController::class, 'customers'])->name('users.customers');
    Route::get('/products/massadd', [MassAddController::class, 'massAddProducts'])->name('products.massadd');

    Route::delete('products/{product}/unfavourite', [ProductController::class, 'unfavourite'])->name('products.unfavourite');
    Route::get('orders/track/{order}', [OrderController::class, 'track'])->name('orders.track');
    Route::post('orders/dispatch', [OrderController::class, 'dispatch'])->name('orders.dispatch.save');
    Route::get('orders/dispatch/{order}', [OrderController::class, 'dispatch'])->name('orders.dispatch');

    Route::resource('users', UserController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('permissions', PermissionController::class);
    Route::resource('products', ProductController::class);
    Route::resource('users', UserController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('favourites', FavouriteController::class);
    Route::resource('estates', EstateController::class);
    Route::resource('refs', RefController::class);
    
    
    Route::get('/home', function () {
        return redirect('/'); 
    })->name('home');
    
    // Route::get('products/admin/show/{product}', [ProductController::class, 'adminShow'])->name("products.admin.show");
});
Route::resource('orders', OrderController::class);
Route::resource('cart_items', CartItemController::class)->except(['delete']);
Route::delete('/cart-items/remove/{product}', [CartItemController::class, 'destroy'])->name('cart_items.destroy');

Route::resource('carts', CartController::class)->except(['show']);
Route::get('/carts/show/{cart?}', [CartController::class, 'show'])->name('carts.show');

Route::get('shop', function () {
    return view('shop')->with('products', Product::all()); 
})->name('shop');

Route::resource('contacts', ContactController::class);

Route::get('/index', function () {
    return redirect('/'); 
})->name('index');

Route::POST('products/search', [ProductController::class, 'search'])->name('products.search');
Route::get('products/search', [ProductController::class, 'search']);


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
