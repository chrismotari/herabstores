# spec.md: Herab Stores migration

Legend: `[ ]` todo · `[~]` in progress · `[x]` done (with date) · `[-]` dropped (with reason)

## Current focus

**Phase 4 → task 4.2**: Filament resources for Products (images, CSV import), Categories, Baskets.

## Goal

Turn the Laravel 11 + Blade monolith into:
1. a Laravel 13 **REST API** (`api/`, `/api/v1`) with a **Filament** admin,
2. an SEO-first **Next.js** storefront (`apps/web`),
3. a **React Native (Expo)** app (`apps/mobile`),

sharing one generated TypeScript client (`packages/shared`). Payments: **pay on delivery** only.

## Progress

| Phase | Status |
|---|---|
| 0. Prep repo for Claude | done |
| 1. Safety net and security fixes | done |
| 2. Upgrade to Laravel 13 | done |
| 3. REST API v1 | done |
| 4. Filament admin | in progress |
| 5. Next.js storefront | todo |
| 6. React Native app | todo |
| 7. Cut-over and cleanup | todo |

---

## Phase 0: Prep repo for Claude

- [x] 0.1 Move Laravel app into `api/` (renames only, history preserved). *2026-11-15*
- [x] 0.2 Add `CLAUDE.md` and `spec.md` at repo root. *2026-11-15*
- [x] 0.3 Make tests use in-memory SQLite (`api/phpunit.xml`). They were wiping the dev DB. *2026-11-15*
- [x] 0.4 Record the test baseline: **4 passed, 21 failed** (Breeze tests don't fill required `users.fname`). *2026-11-15*
- [x] 0.5 Open a PR for Phase 0 and merge it. *2026-11-15*: #5. Phase 1 went in as #11.
- [x] 0.6 Add a GitHub Actions CI workflow for `api/` that runs Pint (`--test`) and Pest.
  - Accept: CI runs on PRs. *2026-11-15*: `.github/workflows/api.yml`. Pint was applied to all of `api/` in one style-only commit.

## Phase 1: Safety net and security fixes (still Laravel 11)

- [x] 1.1 Fix the Breeze auth/profile tests to match the custom `users` schema (fname/lname/phone/country_id). Update `UserFactory`.
  - Accept: `./vendor/bin/pest` passes. *2026-11-15*: 25/25. Also fixed: registration redirected new customers to the admin-only dashboard (403), and profile update dropped the non-existent `name` field.
- [x] 1.2 Add factories for Product, Category, Cart, CartItem, Order, Favourite, Basket, DeliveryZone, County, Estate. *2026-11-15*: plus BasketProduct and a smoke test for each. Estate and BasketProduct got the `UUID` trait (inserts without an id failed). `Order` ids are now strings, not UUID objects.
- [x] 1.3 Characterization tests for current web flows: guest cart add/remove, logged-in cart, checkout → order, favourites, baskets, product search.
  - Accept: each flow has at least one passing feature test that captures today's behaviour. *2026-11-15*: `tests/Feature/Flows/*`. Fixed along the way: removing from a logged-in cart crashed (`User::cartItems()` doesn't exist), and GET `/products/search` returned 404 (it was shadowed by `/products/{product?}`).
- [x] 1.4 **Security:** put `admin` middleware on admin-only resources (users, roles, permissions, products create/edit, categories, refs, estates, delivery_zones, counties, masscreate).
  - Accept: tests show a customer gets 403 and an admin gets 200. *2026-11-15*: `routes/web.php` split into admin / auth / public groups. Contacts (customer PII) are admin-only except the public form. `tests/Feature/Security/AccessControlTest.php`.
- [x] 1.5 **Security:** `Route::resource('orders')` is public, and `GET /orders` lists **every order with customer PII** to anyone. `GET /carts` (CartController@index) lists all carts publicly too. Require auth and scope orders to their owner (policy), with admin seeing all. Keep `orders.create`/`orders.store` open to guests (guest checkout). *2026-11-15*: `OrderPolicy` and `UserPolicy` (owner or admin, via `is_admin` for now; moves to spatie roles in Phase 3/4). The customer profile page also crashed for users without a country.
- [x] 1.6 Fix `Product::getIsInStockAttribute`, which always returns "In Stock" because of an early return. *2026-11-15*: `total_stock` is never maintained (always 0), so the old logic would have marked everything out of stock. Now: < 1 → Out of Stock; ≤ 10% of `total_stock` (or ≤ 5 when it's unset) → Few Items Left. Adding to the cart still ignores stock; enforce it in 3.6.
- [x] 1.7 Remove the duplicate `Route::resource('users')` and dead code (`testRequest.php`, `layouts/app-old.blade.php`, unused Breeze `auth/register.blade.php`). *2026-11-15*: the duplicate route went in 1.4. Deleted those files plus `users/formOLD.blade.php`.
- [x] 1.8 **Bug + security:** sign-up was broken (form sent `estate_id`, validation wanted `estate`), and POST `/register` used the admin `UserController@store`, which accepted `role_id`, so anyone could sign up with any role. *2026-11-15*: sign-up now goes to `RegisteredUserController@store` with `RegisterRequest` (fname, lname, phone, email, password only). Address is collected at checkout, and `users.address`/`city` are now nullable.
- [x] 1.9 **Bug:** checkout (`OrderController@store`) can't work. It passes `fname`/`lname`/`shipping_fees`, which aren't in `Order::$fillable` (that has `first_name`/`last_name`; the column is `shipping_fee`), never sets the required `delivery_zone` column, and reads `estate_id`, but `orders.estate_id` is commented out of the migration. Fix it alongside the 1.3 checkout test. It should also save the delivery address to the user when they have none (decision: address collected at checkout). *2026-11-15*: `PlaceOrderRequest` + rewritten `OrderController@store` (transaction, pay on delivery, prices from products, estate fee → zone fee fallback, first address saved to the user). The order page shows the real shipping fee instead of a hard-coded 150.
- [x] 1.10 Delivery pricing has two sources: estate `delivery_fees` and zone `delivery_fees`. Checkout asks for both. Settle on one model before the API (3.12). *2026-11-15*: **per zone**. Checkout drops the estate field, lists only active zones, and the server charges the zone fee. `estates.delivery_fees` is unused now; drop it in Phase 4 when the admin moves to Filament.

## Phase 2: Upgrade to Laravel 13

- [x] 2.1 Upgrade 11 → 12 (follow the official upgrade guide). Bump Pest, Sanctum, spatie/permission, Tinker and Collision.
  - Accept: tests green, the site works manually (home, shop, product, cart, checkout, admin). *2026-11-15*: v12.72.0. No dependency conflicts, no code changes needed (no `HasUuids`, Carbon 3 already in). 136/136 tests pass, and an HTTP smoke test as guest/customer/admin passes. Note: in 12 the `image` rule rejects SVG, so the SVG listed in `UserRequest` mimes is now refused (safer).
- [x] 2.2 Upgrade 12 → 13. Set `"php": "^8.3"` or higher in composer.json.
  - Accept: tests green, `php artisan about` shows 13.x. *2026-11-15*: v13.42.0, `php: ^8.3`. Pest 3 → 4 (PHPUnit 12), Tinker 2 → 3, Guzzle 7 → 8 (pulled in by the framework). spatie/laravel-permission **kept on 6.25** (it supports 13; the major bump is deferred to Phase 4). No code changes, no deprecations. Route and config caching work, and the HTTP smoke test matches 12.
- [x] 2.3 Remove unused packages: `inertiajs/inertia-laravel`, `tightenco/ziggy`, `laravel/breeze`, `HandleInertiaRequests`, `resources/js/**/*.vue`, and the Vue npm deps.
  - Accept: `npm run build` and tests pass, no Inertia references remain. *2026-11-15*: also removed `views/app.blade.php`, `views/welcome.blade.php` and `jsconfig.json`. Kept Vite + Alpine + Tailwind, because the Blade auth pages (`layouts/guest`) use them. They go in 7.3.
- [x] 2.4 Decide on the production DB (SQLite → MySQL/Postgres?) and record the choice in the Decision log. *2026-11-15*: **PostgreSQL** (local 18; dev DB `herabs`, test DB `herabs_test`, CI uses a `postgres:18` service). Postgres exposed real bugs: corrupted country seed rows (a stray `' iso3'` key and an old "id"→"iso2" find-and-replace), a categories self-FK created before its PK, `orders.order_number` typed as uuid while the codes are 5-char hex (every order would fail), and non-UUID ids causing 500s (now 404 / validation errors).

## Phase 3: REST API v1

### 3a. Foundation
- [x] 3.1 Enable `routes/api.php` (`php artisan install:api`), add the `/api/v1` prefix and `App\Http\Controllers\Api\V1` namespace. *2026-11-15*: `routes/api.php` (v1 prefix, `api.v1.*` names). `api/*` always renders JSON errors. User has `HasApiTokens`. The token table was recreated with `uuidMorphs`: the integer morphs meant UUID users could never get a token.
- [x] 3.2 Configure Sanctum for the SPA (stateful domains for `apps/web`, CORS) and token auth for mobile. *2026-11-15*: `statefulApi()`; CORS for `api/*` and `sanctum/csrf-cookie` with credentials, origins from `FRONTEND_URL` / `CORS_ALLOWED_ORIGINS`; `SANCTUM_STATEFUL_DOMAINS`. Mobile tokens expire after 90 days (`SANCTUM_TOKEN_EXPIRATION`). `GET /api/v1/me` works with either mode.
- [x] 3.3 Add Scramble (OpenAPI) at `/docs/api`, and export `openapi.json` in CI. *2026-11-15*: documents `api/v1` with a bearer security scheme. Docs are open locally and admin-only elsewhere. `composer openapi` writes `api/openapi.json` (committed), and CI fails if it's stale.
- [x] 3.4 Rate limiting (auth, search, cart) and an API exception rendering check (JSON for `api/*`). *2026-11-15*: `api` limiter, 120/min per user or IP, on every API route (`throttleApi()`). `auth` (5/min per identifier+IP, 20/min per IP) and `search` (60/min) limiters are defined; apply them in 3.8 and 3.9. JSON errors were done in 3.1.

### 3b. Extract domain logic
- [x] 3.5 Move cart logic into `app/Actions/Cart/*` (add, update qty, remove, merge guest→user), used by both web and API controllers. *2026-11-15*: guest carts moved from the session array into the DB (`carts.token`), resolved by `CartService::current()` from the `X-Cart-Token` header (API) or the session (web). Actions: `AddItemToCart` (adding again increases the quantity; it used to error "already in cart"), `UpdateCartItem`, `RemoveCartItem`, `MergeGuestCart` (runs on web login via a listener; quantities capped at stock). Stock and active status are enforced when adding. The cart page no longer shows a hard-coded 150 shipping.
- [x] 3.6 Move checkout/order placement into `app/Actions/Orders/PlaceOrder` (pay on delivery, delivery zone fee, stock decrement in a transaction). *2026-11-15*: locks products (`FOR UPDATE`, stable order), rejects inactive or short-stock items with a `cart` error and no side effects, snapshots prices, decrements stock, saves the first address, empties or deletes the cart, and records marketing consent. `OrderController@store` is now a thin wrapper.
- [x] 3.7 Move product import out of `MassAddController` (467 lines) into an Action or a queued job. *2026-11-15*: it wasn't an importer but a hard-coded list of 71 starter products run via `GET /masscreate` (and it crashed on a second run). Now `Database\Seeders\CatalogSeeder`: idempotent by slug, never overwrites admin changes, called from `DatabaseSeeder`. Routes and controller removed. Real CSV import → Filament import action (4.2).

### 3c. Endpoints (each has a Resource, a Form Request, a Policy and Pest tests)
- [x] 3.8 Auth: register, login (token + SPA), logout, me, forgot/reset password, verify email. *2026-11-15*: `Api\V1\AuthController` under `/api/v1/auth/*` + `GET /me` (`UserResource`, no admin flag). `device_name` → bearer token; omitted → SPA session. Login/register merge the `X-Cart-Token` guest cart. `throttle:auth` on public endpoints. Logout revokes only the current token. Password reset signs out all devices. Forgot-password doesn't reveal whether an email exists. Inactive users can't log in. The reset/verify email links still point to the Blade pages (see 5.8).
- [x] 3.9 Catalog (public): `GET categories`, `GET categories/{slug}`, `GET products` (paginate, filter by category/price, sort, search), `GET products/{slug}`.
  - Accept: lookups by slug, no N+1 queries (tested), `Cache-Control` headers set. *2026-11-15*: only active products in active categories (`Product::available()`). Filters: `q` (ILIKE, wildcards escaped), `category` (slug), `min_price`/`max_price`, `in_stock`, `sort` (newest/price_asc/price_desc/name), `per_page` ≤ 60. `Cache-Control: public, max-age=60, s-maxage=300` + ETag. `throttle:search` on the listing. `App\Support\MediaUrl` turns stored paths into full URLs.
- [x] 3.10 Baskets and offers (public read). *2026-11-15*: `GET /api/v1/baskets` and `/baskets/{slug}` (active, within the effective window, with active items). **Offers left out**: there's no working offers feature (empty `OfferController`, nothing applies discounts; the "50% OFF" banners are static theme text).
- [x] 3.10a **Owner decision:** purchasable baskets? offers/discount codes? *2026-11-15*: baskets **yes**, offers **later** (see the Backlog).
- [x] 3.16 Purchasable baskets. *2026-11-15*: a basket is one cart line at its own price and stock (`cart_items.basket_id`; a DB check constraint means each line is exactly one product or one basket). `POST /api/v1/cart/items` takes `product_id` or `basket_id`. Checkout locks and decrements basket stock (the contents aren't deducted separately), and order lines record `type`/`basket_id`. Web cart/checkout/header render basket lines, and web removal is now by line id (`cart_items.remove`).
- [x] 3.11 Guest cart: `POST carts` returns a `cart_token`. Cart endpoints accept the `X-Cart-Token` header. The guest cart merges into the user's cart at login. *2026-11-15*: `/api/v1/cart` (GET, POST → 201 with a guest token, DELETE clears) and `/cart/items` (POST adds, PATCH `{quantity}` with 0 removing, DELETE). Items can only be changed through the cart that owns them (404 otherwise). Works for guests (`X-Cart-Token`), bearer tokens and SPA sessions. API login merges carts (3.8).
- [x] 3.12 Checkout: `POST orders` (pay on delivery). Delivery locations: `GET counties`, `GET counties/{id}/estates`, `GET delivery-zones` (with fee). *2026-11-15*: `POST /api/v1/orders` (PlaceOrder, 201 + `OrderResource` with subtotal/delivery_fee/total, 10/min) and `GET /api/v1/delivery-zones`. Counties and estates aren't exposed, since pricing is per zone and checkout no longer asks for them.
- [x] 3.13 Customer account: my orders (list, show, track), favourites (add/remove/list), profile update, addresses. *2026-11-15*: `/api/v1/me`: PATCH profile + default address (email change → unverified), PUT password (revokes other tokens), DELETE account, `orders` (own only; others' are 404), `favourites` (idempotent add/remove; unavailable products hidden). New `DeleteAccount` action, also used by the web profile page, which used to crash for users with orders. Orders are kept but unlinked. Tracking = order `status` + timestamps.
- [x] 3.14 Contact form `POST contacts`. *2026-11-15*: `POST /api/v1/contacts` (5/min) with a shared `ContactRequest` (the web form now uses it too: only allowed fields are stored). Fixed: `contacts.email` was NOT NULL, so checkout consent from a customer without an email would have failed the whole order.
- [x] 3.15 SEO support: `GET seo/sitemap` (slugs + `updated_at` for products and categories), and a revalidation webhook that calls the Next.js `/api/revalidate` when a product or category is saved (queued, signed secret). *2026-11-15*: `GET /api/v1/seo/sitemap` (products/categories/baskets: slug + updated_at). `StorefrontCacheObserver` on Product/Category/Basket queues `RevalidateStorefront` after commit. Contract: `POST {FRONTEND_URL}/api/revalidate`, `Authorization: Bearer {STOREFRONT_REVALIDATE_SECRET}`, body `{"tags": [...]}` with tags `products`, `categories`, `baskets`, `product:{slug}`, `category:{slug}`, `basket:{slug}` (old slugs included on rename). 5 retries with backoff; does nothing when unconfigured.

## Phase 4: Filament admin

- [x] 4.1 Install the latest Filament compatible with Laravel 13 at `/admin`. Access only for the admin role (`canAccessPanel`). *2026-11-15*: Filament 5.11.3 (needs PHP `intl` + `zip`, added to CI). `User` implements `FilamentUser`/`HasName`; access = `is_admin && is_active` (moves to spatie roles once role management is in Filament, 4.4). The old `/admin` → `/dashboard` redirect is removed; the Blade admin stays at `/dashboard` until 4.6. Same session as the site, so admins logged in on the storefront go straight in.
- [ ] 4.2 Resources: Products (multi-image upload, fixes the README TODO; CSV import action), Categories, Baskets, Offers/OfferTypes.
- [ ] 4.3 Resources: Orders (status workflow, dispatch to courier, tracking), Couriers, Customers.
- [x] 4.0 Upgrade spatie/laravel-permission 6 → 8 (read both upgrade guides; custom UUID `Role`/`Permission` models) before using it in Filament. *2026-11-15*: 8.5.0. No breaking changes affect us (no custom contracts, events, commands or middleware). Added the new config keys (`models.team`, `models.default_model`, `team_resolver`, `events_enabled`). Schema unchanged (ours uses UUIDs). Tests cover UUID roles and permissions. Also fixed: admin user creation always failed on Postgres (`dob` stored `strtotime()` output in a date column).
- [ ] 4.4 Resources: Delivery zones, Counties, Estates, Refs, Users, Roles/Permissions (spatie plugin), Contacts inbox.
- [ ] 4.5 Dashboard widgets: orders today, revenue, low stock.
- [ ] 4.6 Remove the Blade admin (`layouts/backend`, admin views/routes, `public/backend` if unused).
  - Accept: everything the old admin did is possible in Filament. Checked against the old routes list.

## Phase 5: Next.js storefront (`apps/web`)

- [ ] 5.1 Monorepo: pnpm workspaces at the root, `apps/web` (Next.js latest stable, TypeScript, App Router, Tailwind), `packages/shared`.
- [ ] 5.2 `packages/shared`: generate a typed API client from `openapi.json` (e.g. `openapi-typescript` + `openapi-fetch`).
- [ ] 5.3 Layout: header, nav, footer and the design system ported from the current theme. Use our own components and don't copy the theme's licensed JS.
- [ ] 5.4 SEO groundwork: `generateMetadata` defaults, `sitemap.ts`, `robots.ts`, Organization JSON-LD, canonical URLs.
- [ ] 5.5 Pages (server-rendered, ISR): home (`index.blade.php`), shop (`shop.blade.php`), category, product detail (Product + Breadcrumb JSON-LD).
- [ ] 5.6 Content pages: about-us, contact-us (+form), terms-and-conditions, faq (currently reuses contact-us; give it real content).
- [ ] 5.7 Cart and checkout (client components, guest cart token in a cookie, county → estate → delivery fee, pay on delivery).
- [ ] 5.8 Auth (Sanctum SPA) and account: orders, order tracking, favourites, profile. Point password-reset and verify-email links at the Next.js pages (`ResetPassword::createUrlUsing`, `VerifyEmail::createUrlUsing`).
- [ ] 5.9 `/api/revalidate` route handler (secret-protected) used by the API webhook from 3.15. Tag every `fetch` with the same tags (see the 3.15 contract) and call `revalidateTag` for each one received.
- [ ] 5.10 404/500 pages, and a `redirects()` map from old URLs (`/products/{uuid}` → `/products/{slug}`, `/index` → `/`, `/carts/show` → `/cart`).
- [ ] 5.11 Quality gate: Lighthouse SEO ≥ 95 and Performance ≥ 90 on home, category and product pages. Structured data passes Google's Rich Results test.

## Phase 6: React Native app (`apps/mobile`)

- [ ] 6.1 Expo app (TypeScript, Expo Router) in the workspace, using `packages/shared`.
- [ ] 6.2 Auth with a Sanctum token stored in `expo-secure-store`.
- [ ] 6.3 Catalog: home, categories, product list/search, product detail.
- [ ] 6.4 Cart and checkout (pay on delivery), with a guest cart token persisted locally.
- [ ] 6.5 Account: orders and tracking, favourites, profile.
- [ ] 6.6 Deep links (`/products/{slug}` opens the app), app icons/splash, EAS build config.

## Phase 7: Cut-over and cleanup

- [ ] 7.1 Run a queue worker in production (revalidation webhooks, emails).
- [ ] 7.1 Deploy: API on `api.<domain>`, Next.js on `<domain>`. Set env/CORS/Sanctum domains for production.
- [ ] 7.2 Switch DNS to Next.js and check that 301s, the sitemap (submitted to Search Console) and canonicals work.
- [ ] 7.3 Remove the Blade storefront views, web routes, `public/ganic-live`, Vite/Tailwind config in `api/`, and `php-flasher`.
- [ ] 7.4 Update `CLAUDE.md` and `README.md` to the final architecture.

---

## Backlog (not scheduled)

- Offers / discount codes: rules (percent/fixed, product/category/cart level), code validation at checkout, usage limits (`offers` table exists, but there's no logic). Owner: "keep it for later" (2026-11-15).
- Baskets on the web storefront (a listing page + add-to-cart). The API already supports them.

## Decision log

| Date | Decision | Why |
|---|---|---|
| 2026-11-15 | Admin moves to **Filament**, not Next.js | Admin needs no SEO, and Filament is far less work |
| 2026-11-15 | Monorepo: Laravel in `api/`, JS apps in `apps/*`, shared code in `packages/*` | Clean boundaries, one repo, shared TS client |
| 2026-11-15 | **Pay on delivery** only for v1 | No payment integration exists. M-Pesa etc. can come later as a new phase |
| 2026-11-15 | Sign-up asks only name, phone, email and password. Address is collected at checkout | Less friction at sign-up |
| 2026-11-15 | Delivery is priced **per delivery zone**. Estates don't carry a fee | One source of truth for fees. Matches the `orders.delivery_zone` column |
| 2026-11-15 | Baskets are sold as one line at the basket's price and stock. Offers deferred | Baskets already have their own price and stock. Offers are a new feature |
| 2026-11-15 | Sanctum: SPA cookies for web, tokens for mobile | First-party clients, no OAuth needed |
| 2026-11-15 | Guest cart via `X-Cart-Token` instead of session | The API must be stateless for mobile |
| 2026-11-15 | Work directly on `main` and commit at every stage | Owner's preference after Phase 1 |
| 2026-11-15 | **PostgreSQL** for dev, test, CI and production. Tests run on Postgres, not SQLite | Strict types and better text search for products. Tests must match prod; SQLite hid real bugs |
| 2026-11-15 | Upgrade Laravel 11 → 12 → 13 one major at a time, after tests exist | Lower risk, and failures are easy to attribute |
