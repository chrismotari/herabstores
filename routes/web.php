<?php

use App\Http\Controllers\EmailSubscriptionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\JobAdvertController;
use App\Http\Controllers\JobApplicationController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RoleController;
use App\Models\EmailSubscription;
use App\Models\Product;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    
    // Route::get('/dashboard', function () {
    //     return view('dashboard');
    // })->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
   
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

   
    

    Route::resource('users', UserController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('permissions', PermissionController::class);
    
});
Route::get('/index', function () {
    return redirect('/home'); 
})->name('index');

Route::get('/home', function () {
    return redirect('/'); 
})->name('home');

Route::get('/', function () {
    return view('index')->with('products', Product::all()); 
});

Route::resource('products', ProductController::class);
Route::resource('email_subscriptions', EmailSubscriptionController::class);

require __DIR__.'/auth.php';
