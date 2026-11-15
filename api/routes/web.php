<?php

use Illuminate\Support\Facades\Route;

/*
| This app is the API (/api/v1) and the Filament admin (/admin).
| The storefront is the Next.js app (apps/web) at FRONTEND_URL.
*/

// Anyone landing on the API's domain goes to the shop.
Route::get('/', fn () => config('services.storefront.url')
    ? redirect()->away(config('services.storefront.url'))
    : response()->json(['name' => config('app.name'), 'api' => url('/api/v1'), 'admin' => url('/admin')])
)->name('home');

// Old bookmark for the admin.
Route::redirect('dashboard', '/admin')->name('dashboard');
