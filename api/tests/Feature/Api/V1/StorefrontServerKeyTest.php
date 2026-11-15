<?php

test('the storefront server key bypasses rate limits; a wrong key does not', function () {
    config(['services.storefront.server_key' => 'server-secret']);

    $this->withHeader('X-Storefront-Key', 'server-secret')->getJson('/api/v1')->assertHeaderMissing('X-RateLimit-Limit');
    $this->withHeader('X-Storefront-Key', 'wrong')->getJson('/api/v1')->assertHeader('X-RateLimit-Limit', 120);
});

test('without a configured key nobody bypasses the limit', function () {
    config(['services.storefront.server_key' => null]);

    $this->withHeader('X-Storefront-Key', '')->getJson('/api/v1')->assertHeader('X-RateLimit-Limit', 120);
});
