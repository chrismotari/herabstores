<?php

use Illuminate\Support\Facades\Route;

/*
| REST API for the Next.js storefront and the React Native app.
| Everything is versioned under /api/v1 and named api.v1.*.
| Controllers live in App\Http\Controllers\Api\V1.
*/

Route::prefix('v1')->name('api.v1.')->group(function () {
    Route::get('/', fn () => ['name' => config('app.name'), 'version' => 'v1'])->name('index');
});
