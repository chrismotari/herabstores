/**
 * Typed client for the Herab Stores REST API (/api/v1).
 *
 * Types come from api/openapi.json: run `pnpm api:types` after API changes.
 *
 * - Web (Next.js): server components call it with no token (public catalog);
 *   browser calls use the Sanctum session cookie (`credentials: "include"`).
 * - Mobile: pass `getAuthToken` (bearer) and `getCartToken` (guest cart).
 */
import createClient, { type Middleware } from "openapi-fetch";
import type { components, paths } from "./schema";

export type { components, paths };
export type { Middleware } from "openapi-fetch";

type Schemas = components["schemas"];
export type Product = Schemas["ProductResource"];
export type Category = Schemas["CategoryResource"];
export type Basket = Schemas["BasketResource"];
export type Cart = Schemas["CartResource"];
export type CartItem = Cart["items"][number];
export type Order = Schemas["OrderResource"];
export type DeliveryZone = Schemas["DeliveryZoneResource"];
export type User = Schemas["UserResource"];
export type PlaceOrderInput = Schemas["PlaceOrderRequest"];

/** Laravel validation error body (422). */
export type ValidationErrors = { message: string; errors: Record<string, string[]> };

export const CART_TOKEN_HEADER = "X-Cart-Token";

export interface ApiClientOptions {
  /** e.g. https://api.example.com/api/v1 */
  baseUrl: string;
  /** Bearer token (mobile). */
  getAuthToken?: () => string | null | undefined | Promise<string | null | undefined>;
  /** Guest cart token (sent as X-Cart-Token). */
  getCartToken?: () => string | null | undefined | Promise<string | null | undefined>;
  /** Send cookies (web SPA session). */
  credentials?: RequestCredentials;
  /** Custom fetch (e.g. Next.js fetch with cache tags). */
  fetch?: typeof globalThis.fetch;
  headers?: Record<string, string>;
}

export function createApiClient(options: ApiClientOptions) {
  const client = createClient<paths>({
    baseUrl: options.baseUrl.replace(/\/$/, ""),
    credentials: options.credentials,
    fetch: options.fetch,
    headers: { Accept: "application/json", ...options.headers },
  });

  const auth: Middleware = {
    async onRequest({ request }) {
      const token = await options.getAuthToken?.();
      if (token) request.headers.set("Authorization", `Bearer ${token}`);
      const cart = await options.getCartToken?.();
      if (cart) request.headers.set(CART_TOKEN_HEADER, cart);
      return request;
    },
  };
  client.use(auth);

  return client;
}

export type ApiClient = ReturnType<typeof createApiClient>;

/** KES price for display, e.g. "KES 1,250". */
export function formatPrice(value: number): string {
  return `KES ${new Intl.NumberFormat("en-KE", { maximumFractionDigits: 2 }).format(value)}`;
}
