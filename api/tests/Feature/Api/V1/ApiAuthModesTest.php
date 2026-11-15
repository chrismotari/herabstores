<?php

use App\Models\User;

test('bearer tokens authenticate (mobile)', function () {
    $user = User::factory()->create();
    $token = $user->createToken('iphone')->plainTextToken;

    $this->withToken($token)
        ->getJson('/api/v1/me')
        ->assertOk()
        ->assertJsonPath('data.id', $user->id);
});

test('unauthenticated API requests get a JSON 401', function () {
    $this->get('/api/v1/me')
        ->assertUnauthorized()
        ->assertJson(['message' => 'Unauthenticated.']);
});

test('revoked tokens stop working', function () {
    $user = User::factory()->create();
    $token = $user->createToken('iphone');
    $token->accessToken->delete();

    $this->withToken($token->plainTextToken)->getJson('/api/v1/me')->assertUnauthorized();
});

test('the SPA can fetch a CSRF cookie', function () {
    $this->withHeader('Origin', 'http://localhost:3000')
        ->get('/sanctum/csrf-cookie')
        ->assertNoContent()
        ->assertCookie('XSRF-TOKEN');
});

test('CORS allows the storefront origin with credentials', function () {
    $this->withHeaders([
        'Origin' => 'http://localhost:3000',
        'Access-Control-Request-Method' => 'GET',
    ])->options('/api/v1/me')
        ->assertHeader('Access-Control-Allow-Origin', 'http://localhost:3000')
        ->assertHeader('Access-Control-Allow-Credentials', 'true');
});

test('CORS does not allow unknown origins', function () {
    $response = $this->withHeaders([
        'Origin' => 'https://evil.example',
        'Access-Control-Request-Method' => 'GET',
    ])->options('/api/v1/me');

    // With one allowed origin the header is fixed to it; the browser blocks the mismatch.
    expect($response->headers->get('Access-Control-Allow-Origin'))->not->toBe('https://evil.example');
});
