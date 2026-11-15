<?php

namespace App\Http\Controllers\Api\V1;

use App\Actions\Cart\MergeGuestCart;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\LoginRequest;
use App\Http\Requests\Api\V1\RegisterRequest;
use App\Http\Resources\V1\UserResource;
use App\Models\User;
use App\Services\CartService;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\PersonalAccessToken;

/**
 * Two modes:
 * - Mobile: send `device_name`, get a bearer `token` back.
 * - Web SPA (Next.js): omit it; call /sanctum/csrf-cookie first and a session cookie is set.
 *
 * Login and register merge a guest cart identified by the X-Cart-Token header.
 */
class AuthController extends Controller
{
    public function __construct(private CartService $carts, private MergeGuestCart $merge) {}

    /**
     * Register a customer.
     *
     * @unauthenticated
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $this->ensureCanAuthenticate($request);

        $user = User::create([
            ...$request->safe()->only(['fname', 'lname', 'phone', 'email']),
            'password' => Hash::make($request->validated('password')),
        ]);

        event(new Registered($user));

        return $this->authenticated($request, $user, 201);
    }

    /**
     * Log in.
     *
     * @unauthenticated
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $this->ensureCanAuthenticate($request);

        $user = User::firstWhere('email', Str::lower($request->validated('email')));

        if (! $user || ! Hash::check($request->validated('password'), $user->password) || ! $user->is_active) {
            throw ValidationException::withMessages(['email' => __('auth.failed')]);
        }

        return $this->authenticated($request, $user);
    }

    /**
     * Log out (revokes the current token, or ends the session).
     */
    public function logout(Request $request): JsonResponse
    {
        $token = $request->user()->currentAccessToken();

        if ($token instanceof PersonalAccessToken) {
            $token->delete();
        } else {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        return response()->json(['message' => 'Logged out.']);
    }

    /**
     * The current user, or null for guests (never 401).
     *
     * For storefront start-up: browsers log every 401 as a console error.
     *
     * @unauthenticated
     */
    public function session(Request $request): JsonResponse
    {
        $user = $request->user('sanctum');

        return response()->json(['data' => $user ? new UserResource($user) : null]);
    }

    /**
     * The current user.
     */
    public function me(Request $request): UserResource
    {
        return new UserResource($request->user());
    }

    /**
     * Send a password reset link.
     *
     * Always responds the same way so it can't be used to find registered emails.
     *
     * @unauthenticated
     */
    public function forgotPassword(Request $request): JsonResponse
    {
        $request->validate(['email' => ['required', 'email']]);

        Password::sendResetLink($request->only('email'));

        return response()->json(['message' => 'If that email is registered, a reset link has been sent.']);
    }

    /**
     * Reset a password with the token from the email.
     *
     * @unauthenticated
     */
    public function resetPassword(Request $request): JsonResponse
    {
        $request->validate([
            'token' => ['required', 'string'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', PasswordRule::defaults()],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill(['password' => Hash::make($password), 'remember_token' => Str::random(60)])->save();
                $user->tokens()->delete(); // sign out every device
                event(new PasswordReset($user));
            }
        );

        if ($status !== Password::PASSWORD_RESET) {
            throw ValidationException::withMessages(['email' => __($status)]);
        }

        return response()->json(['message' => __($status)]);
    }

    /**
     * Resend the email verification link.
     */
    public function sendVerificationEmail(Request $request): JsonResponse
    {
        if (! $request->user()->hasVerifiedEmail()) {
            $request->user()->sendEmailVerificationNotification();
        }

        return response()->json(['message' => 'Verification link sent.']);
    }

    private function ensureCanAuthenticate(Request $request): void
    {
        if (! $request->filled('device_name') && ! $request->hasSession()) {
            throw ValidationException::withMessages([
                'device_name' => 'device_name is required unless you are using the web session (Sanctum SPA).',
            ]);
        }
    }

    private function authenticated(Request $request, User $user, int $status = 200): JsonResponse
    {
        if ($guest = $this->carts->guestCart($request->header(CartService::HEADER))) {
            ($this->merge)($guest, $user);
        }

        $body = ['data' => new UserResource($user)];

        if ($request->filled('device_name')) {
            $body['token'] = $user->createToken($request->input('device_name'))->plainTextToken;
        } else {
            Auth::guard('web')->login($user); // also merges a session guest cart (MergeGuestCartOnLogin)
            $request->session()->regenerate();
        }

        return response()->json($body, $status);
    }
}
