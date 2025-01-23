<?php

namespace App\Providers;

use App\Models\Company;
use App\Models\ContractType;
use App\Models\Country;
use App\Models\JobAdvert;
use App\Models\JobCategory;
use App\Models\Profession;
use App\Models\Role;
use App\Models\State;
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
                'states' => State::all(),
                'professions' => Profession::all(),
                'job_categories' => JobCategory::all(),
                'contract_types' => ContractType::all(),
                'roles' => Role::all(),
            ]); 
        });

        View::composer('job_adverts.form', function ($view) {
            $view->with([
                'countries' => Country::all(),
                'job_categories' => JobCategory::all(),
                'contract_types' => ContractType::all(),
                'professions' => Profession::all(),
                'companies' => Company::all(),
                'jobs' => JobAdvert::all(),
            ]);
        });
    }
}
