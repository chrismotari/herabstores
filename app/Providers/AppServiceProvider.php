<?php

namespace App\Providers;

use App\Models\Company;
use App\Models\ContractType;
use App\Models\Country;
use App\Models\Role;
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
                'countries' => Country::all(), 
                'roles' => Role::all(),
            ]); 
        });

    }
}
