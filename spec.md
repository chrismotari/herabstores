# spec.md: Herab Stores migration

Legend: `[ ]` todo · `[~]` in progress · `[x]` done (with date) · `[-]` dropped (with reason)

## Current focus

**Phase 6 → task 6.4**: mobile cart and checkout (pay on delivery, guest cart token). Open from 5.11: re-run Lighthouse on catalog pages once real product photos are uploaded.

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
| 4. Filament admin | done |
| 5. Next.js storefront | in progress |
| 6. React Native app | in progress |
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
- [x] 3.17 Baskets on the web storefront (from the backlog). *2026-11-15*: `/baskets` (grid) and `/baskets/[slug]` (contents linked to products, availability date, add to cart; prerendered, real 404 for unknown/expired), "Baskets" in the main menu, baskets in the sitemap, cart lines link to their basket. Loaders tagged `baskets` / `basket:{slug}`, cached for hours (availability windows open and close on a schedule).
- [x] 3.18 Discount codes (from the backlog; owner: "do the backlog", 2026-11-15). Rules are in the decision log.
  - API: `offers` became redeemable codes (`discount_type` percent|fixed, unique upper-case `code`, optional category/product scope, minimum order, window, `max_uses`, `max_uses_per_user`). `App\Services\DiscountCalculator` holds the rules. `POST/DELETE /api/v1/cart/discount`; the cart returns `discount {code, name, amount, error}` and `total`. `PlaceOrder` locks the offer row, re-validates (guests by phone) and stores `offer_id`, `discount_code`, `discount`. `Order::amount_due` = items - discount + delivery, used by the API, Filament (order page, list, dashboard sales) and the Blade order page.
  - Admin: Filament "Discount codes" (Sales group) with scope picker, limits, and uses / discount given per code.
  - Web: discount box on `/cart` and `/checkout`, discount lines in the totals, confirmation and order history; mini cart shows the total after discount.
  - Found while testing in a browser: the cart totals and quantity stepper were unstyled (the theme only styles them inside its own wrappers; now matched), and the cart table was 600 px wide on phones (now name/quantity/subtotal with a "Remove" link).
  - Found while building: prerendering ~80 pages from one IP tripped the API's 120/min limit. The Next.js server now sends `X-Storefront-Key` (`API_SERVER_KEY` = API's `STOREFRONT_SERVER_KEY`) and is exempt from per-IP limits.
  - Dev data left for trying it out: basket "Weekly Veg Box" and code `WELCOME10` (10%, once per customer). Delete them in the admin if unwanted.
- [x] 3.11 Guest cart: `POST carts` returns a `cart_token`. Cart endpoints accept the `X-Cart-Token` header. The guest cart merges into the user's cart at login. *2026-11-15*: `/api/v1/cart` (GET, POST → 201 with a guest token, DELETE clears) and `/cart/items` (POST adds, PATCH `{quantity}` with 0 removing, DELETE). Items can only be changed through the cart that owns them (404 otherwise). Works for guests (`X-Cart-Token`), bearer tokens and SPA sessions. API login merges carts (3.8).
- [x] 3.12 Checkout: `POST orders` (pay on delivery). Delivery locations: `GET counties`, `GET counties/{id}/estates`, `GET delivery-zones` (with fee). *2026-11-15*: `POST /api/v1/orders` (PlaceOrder, 201 + `OrderResource` with subtotal/delivery_fee/total, 10/min) and `GET /api/v1/delivery-zones`. Counties and estates aren't exposed, since pricing is per zone and checkout no longer asks for them.
- [x] 3.13 Customer account: my orders (list, show, track), favourites (add/remove/list), profile update, addresses. *2026-11-15*: `/api/v1/me`: PATCH profile + default address (email change → unverified), PUT password (revokes other tokens), DELETE account, `orders` (own only; others' are 404), `favourites` (idempotent add/remove; unavailable products hidden). New `DeleteAccount` action, also used by the web profile page, which used to crash for users with orders. Orders are kept but unlinked. Tracking = order `status` + timestamps.
- [x] 3.14 Contact form `POST contacts`. *2026-11-15*: `POST /api/v1/contacts` (5/min) with a shared `ContactRequest` (the web form now uses it too: only allowed fields are stored). Fixed: `contacts.email` was NOT NULL, so checkout consent from a customer without an email would have failed the whole order.
- [x] 3.15 SEO support: `GET seo/sitemap` (slugs + `updated_at` for products and categories), and a revalidation webhook that calls the Next.js `/api/revalidate` when a product or category is saved (queued, signed secret). *2026-11-15*: `GET /api/v1/seo/sitemap` (products/categories/baskets: slug + updated_at). `StorefrontCacheObserver` on Product/Category/Basket queues `RevalidateStorefront` after commit. Contract: `POST {FRONTEND_URL}/api/revalidate`, `Authorization: Bearer {STOREFRONT_REVALIDATE_SECRET}`, body `{"tags": [...]}` with tags `products`, `categories`, `baskets`, `product:{slug}`, `category:{slug}`, `basket:{slug}` (old slugs included on rename). 5 retries with backoff; does nothing when unconfigured.

## Phase 4: Filament admin

- [x] 4.1 Install the latest Filament compatible with Laravel 13 at `/admin`. Access only for the admin role (`canAccessPanel`). *2026-11-15*: Filament 5.11.3 (needs PHP `intl` + `zip`, added to CI). `User` implements `FilamentUser`/`HasName`; access = `is_admin && is_active` (moves to spatie roles once role management is in Filament, 4.4). The old `/admin` → `/dashboard` redirect is removed; the Blade admin stays at `/dashboard` until 4.6. Same session as the site, so admins logged in on the storefront go straight in.
- [x] 4.2 Resources: Products (multi-image upload, fixes the README TODO; CSV import action), Categories, Baskets. *2026-11-15*: Filament resources under `app/Filament/Resources` ("Catalogue" group). Products: auto-slug, category picker, KES prices, main image + reorderable gallery (dropped the `product_images` unique sort index, which broke reordering), stock badges, filters, bulk show/hide instead of bulk delete. Deleting a product cleans up images, cart lines, favourites and basket entries. Categories: parent picker, product counts; can't delete while it has products. Baskets: availability window + contents repeater. `ProductImporter` CSV import (matches on slug; blank stock/visible keeps current values; category by slug or name) with in-panel completion notices. Fixed Filament/Laravel stubs for UUID users (`imports`/`exports.user_id`, `notifications` `uuidMorphs`) and `notifications.data` → `jsonb` (Filament queries it as JSON). Offers/OfferTypes are left out (Backlog).
- [x] 4.3 Resources: Orders (status workflow, dispatch to courier, tracking), Couriers, Customers. *2026-11-15*: `OrderStatus` enum (new → dispatched → delivered; new/dispatched → canceled) with guarded transitions. Actions `DispatchOrder` (courier, store, `shipped_at`), `MarkOrderDelivered` (also marks the COD payment paid), `CancelOrder` (locks, restocks products and baskets). Filament: Orders (list with status/zone/date filters + a new-order badge, view page with workflow buttons; no create), Couriers, Customers (non-admins; edit contact, address, active). Fixed: the Blade dispatch never worked (it posted a disabled field to a route with no order); `Courier` had the wrong fillable fields and no UUID trait.
- [x] 4.0 Upgrade spatie/laravel-permission 6 → 8 (read both upgrade guides; custom UUID `Role`/`Permission` models) before using it in Filament. *2026-11-15*: 8.5.0. No breaking changes affect us (no custom contracts, events, commands or middleware). Added the new config keys (`models.team`, `models.default_model`, `team_resolver`, `events_enabled`). Schema unchanged (ours uses UUIDs). Tests cover UUID roles and permissions. Also fixed: admin user creation always failed on Postgres (`dob` stored `strtotime()` output in a date column).
- [x] 4.4 Resources: Delivery zones, Counties, Estates, Refs, Users, Roles/Permissions (spatie plugin), Contacts inbox. *2026-11-15*: plus Stores. Groups: Catalogue / Sales / Delivery / Access. Staff = admin users (hashed password, roles, can't deactivate or delete yourself); Customers stay separate. Roles get permissions via a checkbox list (plain resources, no plugin). Contacts inbox is view + delete only. Fixed: `Estate` had no fillable fields; `DeliveryZone`, `County` and `Store` fillables/relations were wrong; `DeliveryZone::orders()` used the wrong key; `Contact` `referree_code` typo; `Ref` ids were UUID objects. Dropped `estates.delivery_fees` (pricing is per zone).
- [x] 4.5 Dashboard widgets: orders today, revenue, low stock. *2026-11-15*: `ShopStats` (orders today, sales today + 7 days excluding canceled, waiting to dispatch, low stock; cards link to filtered lists; refreshes every 60s), `LatestOrders`, `LowStockProducts` (stock editable inline).
- [x] 4.6 Remove the Blade admin (`layouts/backend`, admin views/routes, `public/backend` if unused).
  - Accept: everything the old admin did is possible in Filament. Checked against the old routes list. *2026-11-15*: removed the admin route group, 14 controllers, ~30 admin views, `layouts/backend` + sidebar, `AdminMiddleware`, unused form requests and the old custom `FormRequest`. `/dashboard` now redirects to `/admin`. Admin links on the storefront (shop, order page) point to Filament. The "all carts" page was dropped (no value). **`public/backend` stays**: the Blade sign-up/login pages use its assets; it goes with the storefront in 7.3. Fixed along the way: the customer profile save and order tracking were both `dd()` stubs.

## Phase 5: Next.js storefront (`apps/web`)

- [x] 5.1 Monorepo: pnpm workspaces at the root, `apps/web` (Next.js latest stable, TypeScript, App Router), `packages/shared`. *2026-11-15*: pnpm 12 (via corepack), Next.js 16.4.6 + React 19.3, `cacheComponents` on. **No Tailwind**: the owner chose to keep the current theme, so its CSS is reused. CI: `.github/workflows/web.yml` (types up to date, typecheck, lint, build).
- [x] 5.2 `packages/shared`: generate a typed API client from `openapi.json` (e.g. `openapi-typescript` + `openapi-fetch`). *2026-11-15*: `createApiClient({ baseUrl, getAuthToken, getCartToken, credentials, fetch })` plus resource type aliases (`Product`, `Cart`, `Order`, ...). `pnpm api:types` regenerates the types.
- [x] 5.3 Layout: header, nav, footer and the design system ported from the current theme. Use our own components and don't copy the theme's licensed JS. Owner (2026-11-15): **keep the current design, with a little tidying.** *2026-11-15*: [x] theme CSS, fonts and the 65 referenced images served from `apps/web/public/theme` (4.8 MB, down from 11+ MB; legacy .eot/.svg fonts dropped). Header (top bar, search as a plain GET form to `/shop`, phone), sticky navbar with **real categories** from the API (replacing the theme's hard-coded demo mega-menu), mobile menu, back-to-top, footer with a working newsletter form (`POST /contacts`). jQuery behaviours rewritten as small client components; preloader removed. Fixed the theme's Google Fonts URL (`&amp;` meant Nunito/Poppins never loaded). Cart/account header widgets are placeholders until 5.7/5.8. **Not yet visually reviewed in a browser.**
- [x] 5.4 SEO groundwork: `generateMetadata` defaults, `sitemap.ts`, `robots.ts`, Organization JSON-LD, canonical URLs. *2026-11-15*: `metadataBase` + title template; canonicals set **per page** (never in the root layout, which would make every page claim to be the home page). `GroceryStore` JSON-LD in the layout; `JsonLd` helper (`<` escaped). `sitemap.xml` from the API feed (79 URLs locally). `robots.txt` disallows cart/checkout/account/auth.
- [x] 5.5 Pages (server-rendered, ISR): home (`index.blade.php`), shop (`shop.blade.php`), category, product detail (Product + Breadcrumb JSON-LD). *2026-11-15*: all data via `'use cache'` loaders tagged `products` / `product:{slug}` / `categories` / `category:{slug}` (`cacheLife('max')`, refreshed by the webhook).
  - Home: one static hero (was a 3-slide carousel of identical slides), real categories, newest products. **Tidied out:** the fake "UP TO 35/50% OFF" promos (now truthful copy) and the "Herabs123 KES 300 coupon" box (no coupon system exists).
  - Shop/category: sidebar of real categories, URL-driven sort/pagination/search (`?q`, `?sort`, `?page`, `?in_stock`); search/filter variants are `noindex`, canonical `/shop`.
  - Product: pre-rendered per product (`generateStaticParams` from the sitemap feed), gallery, Product/Offer/BreadcrumbList JSON-LD, add to cart. `ensureStatic = 'navigation'` so unknown slugs return a **real 404**. Unknown *category* slugs return 200 + noindex (soft 404), because category pages read `searchParams`.
  - **Removed the fake 5-star ratings** on every product (no reviews exist; also bad for Rich Results).
  - Browser cart layer built early for add-to-cart: `lib/browser-api.ts` (session cookie + Sanctum CSRF + `X-Cart-Token` from localStorage) and `CartProvider` (live header count). Verified the CORS/CSRF flow against the API.
- [x] 5.6 Content pages: about-us, contact-us (+form), terms-and-conditions, faq (currently reuses contact-us; give it real content). *2026-11-15*: all static. Contact form posts to `/api/v1/contacts`. **Copy tidied to match reality:** removed "exclusive deals & discounts" (no offers), "multiple payment methods" (pay on delivery only), "24/7 support" (real hours shown); fixed the contact email (`hello.com` → info@herabgroceries.com). New FAQ (delivery zones/fees, pay on delivery, guest checkout, tracking, 24h complaints, changes before dispatch). **Owner to review the FAQ wording.**
- [x] 5.7 Cart and checkout (client components, guest cart token, delivery zone fee, pay on delivery). *2026-11-15*: theme mini cart in the header (hover; items, remove, total), `/cart` (quantities, remove, subtotal), `/checkout` (prefills logged-in customers' details and zone, zone picker with fees, live total, per-field API validation errors, stock errors), `/checkout/confirmation` (summary from the order response). All noindex. The guest token lives in **localStorage** (not a cookie: only the browser needs it). Zones are a cached server loader (hourly). API: baskets now expose `available_quantity`. Verified a full guest checkout via the API (the test order was canceled, which restored stock). Referrer (`ref_id`) isn't offered at checkout yet.
- [x] 5.8 Auth (Sanctum SPA) and account: orders, order tracking, favourites, profile. Point password-reset and verify-email links at the Next.js pages (`ResetPassword::createUrlUsing`, `VerifyEmail::createUrlUsing`). *2026-11-15*: `SessionProvider` (Sanctum SPA cookies; login/register send the guest cart token, so the API merges it, then the local token is cleared). Pages: `/login`, `/register`, `/forgot-password`, `/reset-password`, `/account` (overview, orders + order status timeline, favourites, profile/password/delete account), all noindex; account pages are guarded client-side (the API enforces access). Header account dropdown; favourite button on product pages. API: reset-password emails link to `{FRONTEND_URL}/reset-password` when configured. Verified the SPA flow (CSRF → login → /me → logout → 401) across ports. **Still open:** email *verification* links point to the Blade route (needs a Next page + signed-URL handling); do it at cut-over (7.x).
- [x] 5.9 `/api/revalidate` route handler (secret-protected) used by the API webhook from 3.15. Tag every `fetch` with the same tags (see the 3.15 contract) and call `revalidateTag` for each one received. *2026-11-15*: `POST /api/revalidate`, bearer `REVALIDATE_SECRET` (constant-time compare; must equal the API's `STOREFRONT_REVALIDATE_SECRET`), whitelisted tag formats, `revalidateTag(tag, 'max')`. Verified end to end: a DB price change stayed cached until the webhook, then showed.
- [x] 5.10 404/500 pages, and a `redirects()` map from old URLs (`/products/{uuid}` → `/products/{slug}`, `/index` → `/`, `/carts/show` → `/cart`). *2026-11-15*: `not-found.tsx` (with search), `error.tsx`, `global-error.tsx`. 308 redirects: `/index`, `/home`, `/carts/show/*`, `/orders/create` → `/checkout`, `/orders/{id}` and `/orders/track/{id}` → `/account/orders/{id}`, `/users/*` → `/account`, `/profile`, `/products/search?search=` → `/shop?q=`, `/dashboard` → API `/admin`. `/products/{uuid}` permanently redirects to the slug (the API now also accepts an id on `GET /products/{slug}`).
- [~] 5.11 Quality gate: Lighthouse SEO ≥ 95 and Performance ≥ 90 on home, category and product pages. Structured data passes Google's Rich Results test.
  - *2026-11-15*, Lighthouse 13 (mobile, simulated throttling), local production build, median of 3 runs:

    | Page | Perf (before → after) | SEO | A11y | Best pr. |
    |---|---|---|---|---|
    | `/` | 56 → **81** | 100 | 96 | 96 |
    | `/shop` | 70 → **71–74** | 92 → 100 | 95 | 96 |
    | `/categories/{slug}` | 60 → **73** | 100 | 95 | 96 |
    | `/products/{slug}` | 86 → **85** | 100 | 96 | 96 |
    | `/faq` | 89 → **89** | 100 | 96 | 96 |
    | `/about-us` | **99** | 100 | 96 | 96 |
  - **SEO ≥ 95: met everywhere.** Performance ≥ 90: **not met** (best 99 on content pages, 71–85 on catalog pages).
  - Fixes made: the 6 theme stylesheets combined into one bundled CSS file (`public/theme/css/theme.css`, imported in the root layout; content-hashed by Next); Google Fonts `@import` chain replaced by self-hosted `next/font` (mapped onto the theme's `--font-family-*` variables); correct logo dimensions (was squashed 187×96 → 177×45); heading order; `/shop` meta description (static metadata; the search-variant noindex is a hoisted `<meta>` in the listing); guest start-up uses new `GET /auth/session` (200 + null) instead of a 401 from `/me` (console errors); single add-to-cart button on grid cards (stepper on the product page); first-row product images eager + `fetchpriority=high`; hero preloaded and converted to WebP (155 KB → 11 KB, 372 KB → 104 KB).
  - *2026-11-15, theme CSS slimming (owner: "now")*: build-time PurgeCSS (`apps/web/scripts/build-theme-css.mjs`, source `apps/web/theme/theme.full.css`) cuts theme CSS from **361 KB to 94 KB**. Attribute-selector rules (`[class*="col-"]` gutters, Flaticon's base rule) are kept via `purgecss ignore` comments; the first pass dropped them (no icons, broken grid gutters), which a 30-shot before/after screenshot diff caught (Playwright + pixel diff). Final diff: 29/30 identical; the 30th is a 3 px animation-timing offset.
  - Visual tidy found in the screenshots: removed the hero's side banners (discounts baked into the images), so the hero title no longer overlaps the picture; theme-coloured add-to-cart buttons on cards (they were unstyled grey); spacing on "Shop by Category"; **two product cards per row on phones**.
  - Lighthouse after slimming (median of 3): home **86**, shop **77**, category **76**, product **92**, FAQ **91**, about **99**; SEO 100 everywhere; FCP 0.9 s. Remaining listing-page cost is product images: every seeded product uses the same large external placeholder PNG. **Re-measure with real product photos** (uploaded to the API's storage) before deciding on more work.
  - Rich Results test is Google's online tool: run it on the production URL after deploy (7.2). Locally verified that Product, Offer, BreadcrumbList and GroceryStore JSON-LD render on the right pages.

## Phase 6: React Native app (`apps/mobile`)

- [x] 6.1 Expo app (TypeScript, Expo Router) in the workspace, using `packages/shared`. *2026-11-15*: Expo SDK 58 (RN 0.88, React 19.3) in `apps/mobile` as `@herabs/mobile`, pnpm isolated installs (no hoisting needed). Native tabs (Home/Shop/Cart/Account, SF Symbols + Material icons), brand colours, TanStack Query, `src/lib/api.ts` (bearer + cart token from `expo-secure-store`). Home lists categories from the API. CI: typecheck, `expo lint`, iOS+Android bundle export. Checked in Expo web against the local API.
  - Images: uploaded image URLs come from `asset()`, which follows the request host, so a phone calling the API by LAN IP gets reachable URLs (checked 6.3).
- [x] 6.2 Auth with a Sanctum token stored in `expo-secure-store`. *2026-11-15*: `src/lib/session.tsx` (TanStack Query `['me']`; a 401 drops the stored token). Sign-in/register modals send `device_name` and the guest cart token (merged, then cleared); forgot-password screen; Account tab with sign out and resend-verification. No API changes. Checked in Expo web: bad password error, token persists across reload, sign out revokes it.
- [x] 6.3 Catalog: home, categories, product list/search, product detail. *2026-11-15*: Home (search, categories, baskets, newest), Shop tab (debounced search, category and sort chips, infinite two-column grid), `/categories/{slug}`, `/products/{slug}` (image pager, stock badge), `/baskets/{slug}` (contents link to products). Same paths as the website, ready for deep links (6.6). Query definitions in `src/lib/catalog.ts`. API: `GET /products` now documents `page` (the web client no longer casts its query). Gotcha: `Link asChild` passes styles through a Slot that rejects style arrays; size linked cards with a wrapping View.
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

(empty)

## Decision log

| Date | Decision | Why |
|---|---|---|
| 2026-11-15 | Mobile data layer: **TanStack Query** over the shared openapi-fetch client; secrets in `expo-secure-store` | Caching, pull-to-refresh and retries for free; tokens stay out of AsyncStorage |
| 2026-11-15 | Mobile uses **native tabs** (`expo-router/native-tabs`); Expo web is only a dev preview | Native look on iOS/Android; the real website is `apps/web` |
| 2026-11-15 | Admin moves to **Filament**, not Next.js | Admin needs no SEO, and Filament is far less work |
| 2026-11-15 | Monorepo: Laravel in `api/`, JS apps in `apps/*`, shared code in `packages/*` | Clean boundaries, one repo, shared TS client |
| 2026-11-15 | **Pay on delivery** only for v1 | No payment integration exists. M-Pesa etc. can come later as a new phase |
| 2026-11-15 | Sign-up asks only name, phone, email and password. Address is collected at checkout | Less friction at sign-up |
| 2026-11-15 | Delivery is priced **per delivery zone**. Estates don't carry a fee | One source of truth for fees. Matches the `orders.delivery_zone` column |
| 2026-11-15 | Baskets are sold as one line at the basket's price and stock. Offers deferred | Baskets already have their own price and stock. Offers are a new feature |
| 2026-11-15 | Admin access stays on `is_admin` + `is_active`; spatie roles/permissions are for finer-grained rights later | Switching every policy to roles now adds risk with no visible benefit |
| 2026-11-15 | Storefront keeps the current theme (CSS reused, markup tidied); no Tailwind | Owner's choice; least visual change for customers |
| 2026-11-15 | Discount codes: entered by the customer (no automatic offers), one per order; percent or fixed; whole cart, one category or one product (baskets only count for whole-cart codes); delivery never discounted; discount capped at the eligible amount; optional minimum order (cart subtotal), window, total uses, uses per customer (account, or phone for guests); canceled orders free their use; a code that stops being valid blocks checkout with a clear message instead of silently dropping | Common, predictable rules; easy to change in `DiscountCalculator` |
| 2026-11-15 | The Next.js server authenticates to the API with a shared key (`X-Storefront-Key`) to skip per-IP rate limits | Builds prerender many pages from one IP |
| 2026-11-15 | Sanctum: SPA cookies for web, tokens for mobile | First-party clients, no OAuth needed |
| 2026-11-15 | Guest cart via `X-Cart-Token` instead of session | The API must be stateless for mobile |
| 2026-11-15 | Work directly on `main` and commit at every stage | Owner's preference after Phase 1 |
| 2026-11-15 | **PostgreSQL** for dev, test, CI and production. Tests run on Postgres, not SQLite | Strict types and better text search for products. Tests must match prod; SQLite hid real bugs |
| 2026-11-15 | Upgrade Laravel 11 → 12 → 13 one major at a time, after tests exist | Lower risk, and failures are easy to attribute |
