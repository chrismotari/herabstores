<?php

use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use App\Services\CartService;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;

const SPA = ['Origin' => 'http://localhost:3000', 'Referer' => 'http://localhost:3000/'];

function registration(array $overrides = []): array
{
    return array_merge([
        'fname' => 'Jane', 'lname' => 'Wanjiku', 'phone' => '0711000000',
        'email' => 'jane@example.com', 'password' => 'password', 'password_confirmation' => 'password',
    ], $overrides);
}

// Register

test('mobile registration returns a token', function () {
    $response = $this->postJson('/api/v1/auth/register', registration(['device_name' => 'pixel']))
        ->assertCreated()
        ->assertJsonPath('data.email', 'jane@example.com')
        ->assertJsonMissingPath('data.password')
        ->assertJsonMissingPath('data.is_admin');

    $this->withToken($response->json('token'))->getJson('/api/v1/me')->assertOk();
});

test('SPA registration starts a session instead of returning a token', function () {
    $this->withHeaders(SPA)->postJson('/api/v1/auth/register', registration())
        ->assertCreated()
        ->assertJsonMissingPath('token');

    $this->assertAuthenticatedAs(User::firstWhere('email', 'jane@example.com'), 'web');
    app('auth')->forgetGuards(); // a real next request reloads the user from the session
    $this->withHeaders(SPA)->getJson('/api/v1/me')->assertOk();
});

test('non-SPA clients must send device_name', function () {
    $this->postJson('/api/v1/auth/register', registration())
        ->assertUnprocessable()
        ->assertJsonValidationErrors('device_name');
});

test('registration validates input and cannot set admin', function () {
    $this->postJson('/api/v1/auth/register', registration(['email' => 'bad', 'device_name' => 'x']))
        ->assertJsonValidationErrors('email');

    $this->postJson('/api/v1/auth/register', registration(['is_admin' => true, 'device_name' => 'x']))->assertCreated();
    expect(User::firstWhere('email', 'jane@example.com')->is_admin)->toBeFalsy();
});

// Login

test('mobile login returns a token', function () {
    $user = User::factory()->create();

    $this->postJson('/api/v1/auth/login', ['email' => $user->email, 'password' => 'password', 'device_name' => 'pixel'])
        ->assertOk()
        ->assertJsonPath('data.id', $user->id)
        ->assertJsonStructure(['token']);
});

test('SPA login starts a session', function () {
    $user = User::factory()->create();

    $this->withHeaders(SPA)
        ->postJson('/api/v1/auth/login', ['email' => $user->email, 'password' => 'password'])
        ->assertOk()
        ->assertJsonMissingPath('token');

    $this->assertAuthenticatedAs($user, 'web');
});

test('wrong password and inactive users are rejected with the same error', function () {
    $user = User::factory()->create();
    $inactive = User::factory()->create(['is_active' => false]);

    $this->postJson('/api/v1/auth/login', ['email' => $user->email, 'password' => 'wrong', 'device_name' => 'x'])
        ->assertJsonValidationErrors(['email' => __('auth.failed')]);
    $this->postJson('/api/v1/auth/login', ['email' => $inactive->email, 'password' => 'password', 'device_name' => 'x'])
        ->assertJsonValidationErrors(['email' => __('auth.failed')]);
});

test('login is rate limited', function () {
    $user = User::factory()->create();

    foreach (range(1, 5) as $i) {
        $this->postJson('/api/v1/auth/login', ['email' => $user->email, 'password' => 'wrong', 'device_name' => 'x']);
    }

    $this->postJson('/api/v1/auth/login', ['email' => $user->email, 'password' => 'password', 'device_name' => 'x'])
        ->assertTooManyRequests();
});

test('login merges the guest cart from X-Cart-Token', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create(['stock' => 10]);
    $guest = Cart::create(['token' => (string) Str::uuid()]);
    $guest->cartItems()->create(['product_id' => $product->id, 'quantity' => 2]);

    $this->withHeader(CartService::HEADER, $guest->token)
        ->postJson('/api/v1/auth/login', ['email' => $user->email, 'password' => 'password', 'device_name' => 'x'])
        ->assertOk();

    expect($user->fresh()->cart->cartItems->sole()->quantity)->toBe(2)
        ->and(Cart::find($guest->id))->toBeNull();
});

// Logout

test('logout revokes only the current token', function () {
    $user = User::factory()->create();
    $phone = $user->createToken('phone')->plainTextToken;
    $tablet = $user->createToken('tablet')->plainTextToken;

    $this->withToken($phone)->postJson('/api/v1/auth/logout')->assertOk();
    app('auth')->forgetGuards();

    $this->withToken($phone)->getJson('/api/v1/me')->assertUnauthorized();
    app('auth')->forgetGuards();
    $this->withToken($tablet)->getJson('/api/v1/me')->assertOk();
});

test('SPA logout ends the session', function () {
    $user = User::factory()->create();
    $this->withHeaders(SPA)->postJson('/api/v1/auth/login', ['email' => $user->email, 'password' => 'password']);

    $this->withHeaders(SPA)->postJson('/api/v1/auth/logout')->assertOk();

    $this->assertGuest('web');
});

// Me

test('me returns the current user', function () {
    $user = User::factory()->create();

    $this->actingAs($user, 'sanctum')->getJson('/api/v1/me')
        ->assertOk()
        ->assertJsonPath('data.id', $user->id)
        ->assertJsonPath('data.email_verified', true);
});

// Passwords

test('forgot-password responds the same for known and unknown emails', function () {
    Notification::fake();
    $user = User::factory()->create();

    $known = $this->postJson('/api/v1/auth/forgot-password', ['email' => $user->email])->assertOk()->json('message');
    $unknown = $this->postJson('/api/v1/auth/forgot-password', ['email' => 'nobody@example.com'])->assertOk()->json('message');

    expect($known)->toBe($unknown);
    Notification::assertSentTo($user, ResetPassword::class);
});

test('password can be reset with a valid token, signing out all devices', function () {
    $user = User::factory()->create();
    $user->createToken('old-phone');
    $token = Password::createToken($user);

    $this->postJson('/api/v1/auth/reset-password', [
        'token' => $token, 'email' => $user->email,
        'password' => 'new-password', 'password_confirmation' => 'new-password',
    ])->assertOk();

    expect(Hash::check('new-password', $user->fresh()->password))->toBeTrue()
        ->and($user->tokens()->count())->toBe(0);
});

test('an invalid reset token is rejected', function () {
    $user = User::factory()->create();

    $this->postJson('/api/v1/auth/reset-password', [
        'token' => 'bad', 'email' => $user->email,
        'password' => 'new-password', 'password_confirmation' => 'new-password',
    ])->assertJsonValidationErrors('email');
});

test('unverified users can request a verification email', function () {
    Notification::fake();
    $user = User::factory()->unverified()->create();

    $this->actingAs($user, 'sanctum')->postJson('/api/v1/auth/email/verification-notification')->assertOk();

    Notification::assertSentTo($user, VerifyEmail::class);
});

test('reset links point to the storefront when configured', function () {
    config(['services.storefront.url' => 'https://shop.example']);
    Notification::fake();
    $user = User::factory()->create(['email' => 'reset@example.com']);

    $this->postJson('/api/v1/auth/forgot-password', ['email' => $user->email]);

    Notification::assertSentTo($user, ResetPassword::class, function (ResetPassword $notification) use ($user) {
        $url = $notification->toMail($user)->actionUrl;

        return str_starts_with($url, 'https://shop.example/reset-password?token=') && str_contains($url, 'email=reset%40example.com');
    });
});

test('session returns the user, or null for guests without a 401', function () {
    $this->getJson('/api/v1/auth/session')->assertOk()->assertJsonPath('data', null);

    $user = User::factory()->create();
    $this->actingAs($user, 'sanctum')->getJson('/api/v1/auth/session')->assertOk()->assertJsonPath('data.id', $user->id);
});
