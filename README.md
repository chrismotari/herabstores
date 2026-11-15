# Herab Stores

Online grocery shop for Nairobi: fresh produce and baskets, delivery by zone, pay on delivery.

- `api/`: Laravel 13 REST API (`/api/v1`, docs at `/docs/api`) and the Filament admin (`/admin`)
- `apps/web`: Next.js storefront (server-rendered for SEO)
- `apps/mobile`: Expo (React Native) app for iOS and Android
- `packages/shared`: typed API client generated from the API's OpenAPI spec
- `deploy/`: how to put it all in production

See [`spec.md`](spec.md) for the plan and progress, [`CLAUDE.md`](CLAUDE.md) for dev commands and
conventions, and [`deploy/README.md`](deploy/README.md) for production.
