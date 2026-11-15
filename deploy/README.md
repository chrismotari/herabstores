# Deploying Herab Stores

Three parts, one repo. Replace `<domain>` with the shop's domain.

| Part | Where | Code |
|---|---|---|
| API + Filament admin | `https://api.<domain>` (`/api/v1`, `/admin`) | `api/` (PHP 8.4, Postgres) |
| Storefront | `https://<domain>` | `apps/web` (Node 24, Next.js) |
| Mobile app | App Store / Play Store | `apps/mobile` (EAS) |

The API and the storefront must share a parent domain (`api.<domain>` and `<domain>`): the website
signs customers in with Sanctum cookies scoped to `.<domain>`.

## 1. API (`api/`)

Server needs: PHP 8.4 with `pgsql`, `intl`, `zip`, `mbstring`; Composer; Postgres; a web server
pointing at `api/public`; Supervisor (or similar) for the queue worker; cron.

`.env` (start from `.env.example`):

```dotenv
APP_ENV=production
APP_DEBUG=false
APP_URL=https://api.<domain>
APP_KEY=                      # php artisan key:generate --show

DB_CONNECTION=pgsql
DB_HOST=... DB_DATABASE=... DB_USERNAME=... DB_PASSWORD=...

FRONTEND_URL=https://<domain>
SANCTUM_STATEFUL_DOMAINS=<domain>,www.<domain>
SESSION_DOMAIN=.<domain>
SESSION_SECURE_COOKIE=true
# CORS_ALLOWED_ORIGINS defaults to FRONTEND_URL; add www.<domain> if it serves the site too.

STOREFRONT_REVALIDATE_SECRET=  # random; same value as the web app's REVALIDATE_SECRET
STOREFRONT_SERVER_KEY=         # random; same value as the web app's API_SERVER_KEY

QUEUE_CONNECTION=database
MAIL_MAILER=smtp               # plus MAIL_HOST/PORT/USERNAME/PASSWORD, MAIL_FROM_ADDRESS
```

Generate the two shared secrets with `openssl rand -hex 32`.

Each deploy:

```bash
cd api
composer install --no-dev --optimize-autoloader   # also publishes Filament assets
php artisan migrate --force
php artisan storage:link                          # first deploy only
php artisan optimize
php artisan queue:restart
```

First deploy only:
- **Don't run `db:seed` in production.** It creates test logins (`admin@mail.com` / `password`) and
  sample products. Countries and Kenyan counties come from the migrations.
- Create the first admin (then add staff, zones and the catalog in `/admin`):

  ```bash
  php artisan tinker --execute '
    App\Models\User::create(["fname" => "Your", "lname" => "Name", "phone" => "07XXXXXXXX",
      "email" => "you@<domain>", "password" => Hash::make(Str::password(20)),
      "country_id" => App\Models\Country::where("name", "Kenya")->value("id")])
      ->forceFill(["is_admin" => true, "is_active" => true, "email_verified_at" => now()])->save();'
  ```

  Then use "Forgot password" on the website to set the password (it's a random one above).

Background work:
- **Queue worker**: [`herabs-worker.conf`](herabs-worker.conf) (Supervisor). Without it, catalog
  edits don't refresh the website and no emails go out.
- **Scheduler**: [`crontab`](crontab). Daily housekeeping (abandoned guest carts after 30 days,
  expired tokens, old failed jobs).

## 2. Storefront (`apps/web`)

Env (build time *and* runtime; `NEXT_PUBLIC_*` and the app-link values are baked in at build):

```dotenv
API_URL=https://api.<domain>/api/v1          # or an internal address for server-side calls
NEXT_PUBLIC_API_URL=https://api.<domain>/api/v1
NEXT_PUBLIC_SITE_URL=https://<domain>
API_SERVER_KEY=          # = API STOREFRONT_SERVER_KEY
REVALIDATE_SECRET=       # = API STOREFRONT_REVALIDATE_SECRET
APPLE_TEAM_ID=           # once the app is in the stores (step 3)
ANDROID_SHA256_CERT_FINGERPRINTS=
```

Build and run (the API must be reachable during the build: it prerenders the catalog):

```bash
corepack enable && pnpm install --frozen-lockfile
pnpm build:web
pnpm --filter @herabs/web start        # port 3000; put a reverse proxy with TLS in front
```

Any Node host works (a VPS with a process manager, or Vercel with root `apps/web`).

After switching DNS (spec 7.3): check old URLs 301 (`/products/search?search=x`, `/orders/track/...`),
`/sitemap.xml` and `/robots.txt`, then submit the sitemap in Google Search Console.

## 3. Mobile app (`apps/mobile`)

One-time: an Expo account, an Apple Developer account and a Google Play developer account.

```bash
cd apps/mobile
npx eas-cli@latest login
npx eas-cli@latest init                # links the project, writes the project id
# EAS dashboard > Environment variables > production:
#   EXPO_PUBLIC_API_URL=https://api.<domain>/api/v1
#   EXPO_PUBLIC_SITE_URL=https://<domain>
npx eas-cli@latest build -p all --profile production
npx eas-cli@latest submit -p all
```

Before submitting, replace `assets/images/icon.png` (and the Android foreground/monochrome icons) with
a square logo mark: the current ones are made from the wide wordmark.

Then make website links open the app: put the Apple Team ID and the Android signing SHA-256
(`npx eas-cli@latest credentials -p android`) into the storefront env (step 2) and rebuild it.
