<?php

namespace App\Providers;

use App\Models\Cart;
use App\Models\Category;
use App\Models\Company;
use App\Models\ContractType;
use App\Models\Country;
use App\Models\Courier;
use App\Models\Estate;
use App\Models\Role;
use App\Models\Store;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);

        View::composer('users.form', function ($view) {
            $view->with([
                'countries' => Country::where('name', 'like', 'Kenya')->get(), 
                'roles' => Role::all(),
                'estates' => Estate::all(),
            ]); 
        });

        View::composer('layouts.includes.header', function ($view) {
            if(auth()->check()) {
                $cart = auth()->user()->cart;
            }else  $cart = session()->get('cart', new Cart());
            // dd($cart);
            $view->with([
                'cart' => $cart, 
                'favourites' => Auth::user()?->favourites, 
                'categories' => Category::all(), 
            ]); 
        });
        View::composer('products.form', function ($view) {
            $view->with([
                'categories' => Category::all(), 
            ]); 
        });
        View::composer('orders.dispatch', function ($view) {
            $view->with([
                'couriers' => Courier::all(),
                'stores' => Store::all(), 
            ]); 
        });

    }
}
