<?php

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;

test('API requests are rate limited per client with a JSON 429', function () {
    RateLimiter::for('api', fn () => Limit::perMinute(3));

    foreach (range(1, 3) as $i) {
        $this->getJson('/api/v1')->assertOk()->assertHeader('X-RateLimit-Limit', 3);
    }

    $this->get('/api/v1')
        ->assertTooManyRequests()
        ->assertHeader('Retry-After')
        ->assertJsonStructure(['message']);
});

test('the default API limit is 120 per minute', function () {
    $this->getJson('/api/v1')->assertHeader('X-RateLimit-Limit', 120);
});
