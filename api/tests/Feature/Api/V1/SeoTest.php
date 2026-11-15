<?php

use App\Jobs\RevalidateStorefront;
use App\Models\Basket;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Http;

test('sitemap lists available products, categories and baskets', function () {
    $product = Product::factory()->create();
    Product::factory()->create(['is_active' => false]);
    Basket::factory()->create(['slug' => 'weekly-veg']);

    $this->getJson('/api/v1/seo/sitemap')
        ->assertOk()
        ->assertJsonCount(1, 'data.products')
        ->assertJsonPath('data.products.0.slug', $product->slug)
        ->assertJsonPath('data.categories.0.slug', $product->category->slug)
        ->assertJsonPath('data.baskets.0.slug', 'weekly-veg')
        ->assertJsonStructure(['data' => ['products' => [['slug', 'updated_at']]]]);
});

test('saving a product revalidates its pages, including the old slug', function () {
    $product = Product::factory()->create(['slug' => 'old-tea']);
    Bus::fake();

    $product->update(['slug' => 'new-tea', 'price' => 99]);

    Bus::assertDispatched(RevalidateStorefront::class, fn ($job) => in_array('product:new-tea', $job->tags)
        && in_array('product:old-tea', $job->tags)
        && in_array('category:'.$product->category->slug, $job->tags)
        && in_array('products', $job->tags));
});

test('category changes revalidate category and product listings', function () {
    $category = Category::factory()->create(['slug' => 'tea']);
    Bus::fake();

    $category->delete();

    Bus::assertDispatched(RevalidateStorefront::class, fn ($job) => in_array('category:tea', $job->tags) && in_array('products', $job->tags));
});

test('the webhook posts tags to Next.js with the shared secret', function () {
    config(['services.storefront.url' => 'https://shop.example', 'services.storefront.revalidate_secret' => 's3cret']);
    Http::fake(['shop.example/*' => Http::response(['revalidated' => true])]);

    (new RevalidateStorefront(['products', 'product:tea', 'products']))->handle();

    Http::assertSent(fn ($request) => $request->url() === 'https://shop.example/api/revalidate'
        && $request->hasHeader('Authorization', 'Bearer s3cret')
        && $request['tags'] === ['products', 'product:tea']);
});

test('the webhook does nothing when not configured', function () {
    config(['services.storefront.revalidate_secret' => null]);
    Http::fake();

    (new RevalidateStorefront(['products']))->handle();

    Http::assertNothingSent();
});

test('a failing webhook throws so the job is retried', function () {
    config(['services.storefront.url' => 'https://shop.example', 'services.storefront.revalidate_secret' => 's3cret']);
    Http::fake(['shop.example/*' => Http::response('down', 503)]);

    expect(fn () => (new RevalidateStorefront(['products']))->handle())
        ->toThrow(RequestException::class);
});
