<?php

use App\Models\User;

test('the v1 index responds with JSON', function () {
    $this->getJson('/api/v1')
        ->assertOk()
        ->assertJson(['version' => 'v1']);
});

test('unknown API routes return a JSON 404 even without an Accept header', function () {
    $this->get('/api/v1/does-not-exist')
        ->assertNotFound()
        ->assertHeader('content-type', 'application/json')
        ->assertJsonStructure(['message']);
});

test('users with uuid keys can be issued API tokens', function () {
    $user = User::factory()->create();

    $token = $user->createToken('test')->plainTextToken;

    expect($token)->toBeString()
        ->and($user->tokens()->count())->toBe(1);
});
