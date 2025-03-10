<?php

namespace App\Providers;

use App\Models\Cart;
use App\Models\CartItem;
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
            $cart = New Cart();
            if(auth()->check()) {
                $cart = auth()->user()->cart;
            }else{
                $cartItems = session()->get('cart', []); // Retrieve cart from session
                $cartCollection = collect($cartItems)->map(function ($item, $productId) {
                    return new CartItem([
                        'product_id' => $productId,
                        'quantity' => $item['quantity'],
                    ]);
                });

                // Attach the collection to the new Cart instance
                $cart->setRelation('cartItems', $cartCollection);
            }  
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
