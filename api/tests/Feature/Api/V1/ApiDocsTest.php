<?php

use App\Models\User;

test('API docs are restricted to admins outside local', function () {
    app()->detectEnvironment(fn () => 'production');

    $this->get('/docs/api')->assertForbidden();
    $this->actingAs(User::factory()->create(['is_admin' => false]))->get('/docs/api')->assertForbidden();
    $this->actingAs(User::factory()->create(['is_admin' => true]))->get('/docs/api')->assertOk();
});

test('the OpenAPI document describes v1', function () {
    app()->detectEnvironment(fn () => 'local');

    $this->getJson('/docs/api.json')
        ->assertOk()
        ->assertJsonPath('info.title', 'Herab Stores API')
        ->assertJsonStructure(['paths' => ['/me']]);
});
