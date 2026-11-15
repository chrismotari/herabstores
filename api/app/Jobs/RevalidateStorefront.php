<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Http;

/**
 * Tells the Next.js storefront which cached pages to rebuild.
 *
 * Contract: POST {FRONTEND_URL}/api/revalidate
 *   Authorization: Bearer {STOREFRONT_REVALIDATE_SECRET}
 *   {"tags": ["products", "product:{slug}", "category:{slug}", ...]}
 * Next.js calls revalidateTag() for each tag. Does nothing when unconfigured.
 */
class RevalidateStorefront implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    public int $tries = 5;

    /** @param  list<string>  $tags */
    public function __construct(public array $tags) {}

    public function backoff(): array
    {
        return [10, 30, 60, 300];
    }

    public function handle(): void
    {
        $url = config('services.storefront.url');
        $secret = config('services.storefront.revalidate_secret');

        if (! $url || ! $secret || ! $this->tags) {
            return;
        }

        Http::withToken($secret)
            ->timeout(10)
            ->post(rtrim($url, '/').'/api/revalidate', ['tags' => array_values(array_unique($this->tags))])
            ->throw();
    }
}
