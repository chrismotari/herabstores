<?php

use App\Http\Controllers\Api\V1\AuthController;
use Illuminate\Support\Facades\Route;

/*
| REST API for the Next.js storefront and the React Native app.
| Everything is versioned under /api/v1 and named api.v1.*.
| Controllers live in App\Http\Controllers\Api\V1.
| Auth: Bearer token (mobile) or Sanctum session cookie (Next.js).
*/

Route::prefix('v1')->name('api.v1.')->group(function () {
    Route::get('/', fn () => ['name' => config('app.name'), 'version' => 'v1'])->name('index');

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

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('me', [AuthController::class, 'me'])->name('me');
    });
});
