"use client";

import { createApiClient, type Middleware, type ValidationErrors } from "@herabs/shared";
import { PUBLIC_API_URL } from "./config";

/**
 * Browser API client.
 *
 * - Sends the Sanctum session cookie (logged-in customers).
 * - Sends X-XSRF-TOKEN on writes; fetches /sanctum/csrf-cookie first if needed.
 * - Sends the guest cart token (localStorage) as X-Cart-Token.
 */
const CART_TOKEN_KEY = "herabs.cartToken";

export const cartToken = {
  get: () => (typeof window === "undefined" ? null : safe(() => localStorage.getItem(CART_TOKEN_KEY))),
  set: (token: string | null) =>
    safe(() => (token ? localStorage.setItem(CART_TOKEN_KEY, token) : localStorage.removeItem(CART_TOKEN_KEY))),
};

function safe<T>(fn: () => T): T | null {
  try {
    return fn();
  } catch {
    return null; // storage blocked (private mode, etc.)
  }
}

const apiOrigin = new URL(PUBLIC_API_URL).origin;

function readXsrfCookie(): string | null {
  const match = document.cookie.match(/(?:^|;\s*)XSRF-TOKEN=([^;]+)/);
  return match ? decodeURIComponent(match[1]) : null;
}

let csrfRequest: Promise<unknown> | null = null;
export async function ensureCsrfCookie() {
  if (readXsrfCookie()) return;
  csrfRequest ??= fetch(`${apiOrigin}/sanctum/csrf-cookie`, { credentials: "include" }).finally(() => (csrfRequest = null));
  await csrfRequest;
}

const csrf: Middleware = {
  async onRequest({ request }) {
    if (request.method !== "GET" && request.method !== "HEAD") {
      await ensureCsrfCookie();
      const token = readXsrfCookie();
      if (token) request.headers.set("X-XSRF-TOKEN", token);
    }
    return request;
  },
};

export const browserApi = createApiClient({
  baseUrl: PUBLIC_API_URL,
  credentials: "include",
  getCartToken: cartToken.get,
});
browserApi.use(csrf);

/** First validation message from a 422 body, or a generic message. */
export function errorMessage(error: unknown, fallback = "Something went wrong. Please try again."): string {
  const body = error as Partial<ValidationErrors> | undefined;
  const first = body?.errors ? Object.values(body.errors).flat()[0] : undefined;
  return first ?? body?.message ?? fallback;
}
