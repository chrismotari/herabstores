<?php

namespace App\Providers;

use App\Models\Basket;
use App\Models\Category;
use App\Models\Country;
use App\Models\DeliveryZone;
use App\Models\Estate;
use App\Models\Product;
use App\Models\Role;
use App\Models\User;
use App\Observers\StorefrontCacheObserver;
use App\Services\CartService;
use Dedoc\Scramble\Scramble;
use Dedoc\Scramble\Support\Generator\OpenApi;
use Dedoc\Scramble\Support\Generator\SecurityScheme;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
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
        $this->configureRateLimiting();

        // Catalog changes revalidate the Next.js storefront (see RevalidateStorefront).
        foreach ([Product::class, Category::class, Basket::class] as $model) {
            $model::observe(StorefrontCacheObserver::class);
        }

        // API docs (/docs/api): open locally, admins only elsewhere.
        Gate::define('viewApiDocs', fn (?User $user = null) => app()->isLocal() || (bool) $user?->is_admin);
        Scramble::configure()->withDocumentTransformers(
            fn (OpenApi $openApi) => $openApi->secure(SecurityScheme::http('bearer'))
        );

        Vite::prefetch(concurrency: 3);

        View::composer('users.form', function ($view) {
            $view->with([
                'countries' => Country::where('name', 'like', 'Kenya')->get(),
                'roles' => Role::all(),
                'estates' => Estate::all(),
            ]);
        });

        View::composer('layouts.includes.header', function ($view) {
            $cart = app(CartService::class)->current(request());

            $view->with([
                'cart' => $cart,
                'favourites' => Auth::user()?->favourites,
                'categories' => Category::all(),
            ]);
        });
        View::composer('orders.create', function ($view) {
            $view->with([
                'delivery_zones' => DeliveryZone::where('is_active', true)->orderBy('name')->get(),
            ]);
        });

    }

    /**
     * api: every /api/v1 request. auth: login/register/password endpoints
     * (per IP + identifier, to slow credential stuffing). search: catalog search.
     */
    protected function configureRateLimiting(): void
    {
        RateLimiter::for('api', fn (Request $request) => Limit::perMinute(120)
            ->by($request->user()?->id ?: $request->ip()));

        RateLimiter::for('auth', fn (Request $request) => [
            Limit::perMinute(5)->by(strtolower((string) ($request->input('email') ?? $request->input('phone'))).'|'.$request->ip()),
            Limit::perMinute(20)->by($request->ip()),
        ]);

        RateLimiter::for('search', fn (Request $request) => Limit::perMinute(60)
            ->by($request->user()?->id ?: $request->ip()));
    }
}
