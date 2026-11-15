# CLAUDE.md

Guidance for Claude (and humans) working in this repo.

## What this is

**Herab Stores**: an online grocery storefront (Kenya; delivery zones by county/estate; pay on delivery).
We are migrating from a Laravel + Blade monolith to:

- `api/`: Laravel (upgrading to **13**), a **REST API** under `/api/v1` plus a **Filament** admin panel at `/admin`
- `apps/web`: **Next.js** (App Router) storefront. **SEO is the top priority.**
- `apps/mobile`: **React Native (Expo)** customer app
- `packages/shared`: TypeScript API client and types generated from the API's OpenAPI spec

`apps/` and `packages/` don't exist yet. They are created by tasks in `spec.md`.

## Read spec.md first

`spec.md` is the source of truth for **what to do next and what is done**.

- Start every session by reading the **Current focus** section of `spec.md`.
- Work on **one task at a time**, in order, unless told otherwise.
- When starting a task, mark it `[~]`. When it's done and its acceptance criteria pass, mark it `[x]`, add the date, and move **Current focus** on.
- If you discover new work, add it as a new task in the right phase. Don't silently expand scope.
- Record non-obvious decisions in the **Decision log** at the bottom of `spec.md`.

## Commands (run from `api/`)

```bash
composer install && npm install
cp .env.example .env && php artisan key:generate
php artisan migrate --seed          # Postgres DB `herabs` (user app / password, see .env.example)
php artisan storage:link            # once: serve uploaded images from public/storage
composer dev                        # serve + queue + pail + vite
./vendor/bin/pest                   # tests run on Postgres DB `herabs_test` (forced in phpunit.xml)
./vendor/bin/pest --filter=Name     # single test
./vendor/bin/pint                   # format PHP (run before committing)
php artisan route:list --path=api   # inspect API routes
composer openapi                    # regenerate api/openapi.json after any API change (CI checks it)
```

Seeded logins: `admin@mail.com` / `password` (admin), `user@mail.com` / `password` (customer).

## Commands (JS: repo root)

```bash
corepack enable --install-directory ~/.local/bin pnpm   # once, if `pnpm` isn't found
pnpm install
pnpm dev:web          # Next.js storefront on :3000 (needs the API on :8000)
pnpm api:types        # regenerate packages/shared/src/schema.d.ts from api/openapi.json (CI checks it)
pnpm typecheck && pnpm lint && pnpm build:web
```

- **Next.js 16 is newer than your training data.** Before writing Next code, read the relevant guide in `apps/web/node_modules/next/dist/docs/` (see `apps/web/AGENTS.md`). `cacheComponents` is on.
- Call the API only through `@herabs/shared` (`createApiClient`), never with hand-written fetch URLs or types.
- **Theme CSS is purged at build time.** Edit `apps/web/theme/theme.full.css` (the full theme); `scripts/build-theme-css.mjs` writes the slimmed, gitignored `public/theme/css/theme.css` before `dev`/`build`. Classes toggled at runtime or built from strings need adding to `SAFELIST` in that script; rules using attribute selectors (`[class*=…]`) need a `/* purgecss ignore */` comment before them in the source. After changing styling, compare screenshots: a missing class fails silently.

## Architecture (api/)

- **Models**: `app/Models`. Most use UUID primary keys via `App\Traits\UUID`. Core: Product, Category, Basket (+BasketProduct), Cart (+CartItem), Order, Favourite, Offer/OfferType, Courier, DeliveryZone, County, Estate, Country, Ref, Store, User.
- **Roles**: `spatie/laravel-permission`, plus a legacy `users.is_admin` flag used by `AdminMiddleware`.
- **Current web layer (legacy storefront, replaced by Next.js in Phase 5)**: `routes/web.php`, Blade views in `resources/views`, controllers in `app/Http/Controllers`. The admin is Filament only.
- **Guest cart** currently lives in the session (`app/Services/CartService.php`). The API replaces this with a cart token.

### Target conventions for new API code
- Routes go in `routes/api.php` under the `v1` prefix and are named `api.v1.*`.
- Controllers go in `app/Http/Controllers/Api/V1`. Keep them thin: validate with a **Form Request**, call an **Action** (`app/Actions/...`), and return an **API Resource**.
- Authorization goes through **Policies** backed by spatie roles/permissions, never inline `is_admin` checks.
- Public catalog endpoints look up records by **slug**, paginate (never `::all()`), and eager-load relations to avoid N+1 queries.
- Auth uses Sanctum: cookie-based sessions for `apps/web`, bearer tokens for `apps/mobile`.
- Every endpoint gets a Pest feature test in `tests/Feature/Api/V1`, and `composer openapi` is re-run and committed. API docs: `/docs/api`.
- Error format: Laravel's default JSON (`message`, `errors`). Don't invent a custom envelope.

### Admin
- Admin is **Filament** (`app/Filament`). The Blade admin has been removed. Don't build admin screens in Blade or Next.js.
- Panel: `/admin` (`App\Providers\Filament\AdminPanelProvider`), Filament 5. Generate resources with `php artisan make:filament-resource`. Requires PHP `intl` and `zip`.

## SEO rules (apps/web)
- Product, category and content pages are **server-rendered** (Server Components with ISR/on-demand revalidation), never client-only.
- Every page sets `generateMetadata`: unique title, description, canonical URL and OpenGraph.
- Products get `Product` JSON-LD, listings get `BreadcrumbList`, the site gets `Organization`.
- URLs: `/products/{slug}`, `/categories/{slug}`. Old URLs get 301 redirects.
- Use `next/image` and keep sitemap/robots generated from the API.

## Guardrails
- **Never** run tests against the dev DB. `phpunit.xml` forces `DB_DATABASE=herabs_test` (`force="true"`), so keep it that way. Tests use `RefreshDatabase` and will wipe whatever DB they hit.
- Database is **PostgreSQL** (dev, test, CI, production). Primary keys are UUIDs, so validate ids with `uuid` before `exists:` (Postgres errors on non-UUID input). Route binding already 404s on non-UUID keys (`App\Traits\UUID`).
- Don't commit `.env`, `vendor/` or `node_modules/`.
- Never commit a `composer.lock` resolved with `--ignore-platform-reqs`. `config.platform.php` pins production PHP (8.4), and an unconstrained resolve breaks CI. Install the missing extension instead.
- Don't edit files under `public/ganic-live`, `public/backend` or `vendor/`. They're third-party theme and library assets.
- Don't delete Blade views or web routes until the matching `spec.md` cut-over task says so. The site must keep working during migration.
- Work directly on `main` (no feature branches or PRs unless asked). Commit at every stage: one commit per spec task (or per step within a large task), with tests and Pint passing first.
