import { createApiClient, type ValidationErrors } from '@herabs/shared';

import { API_URL } from './config';
import { storage } from './storage';

export const AUTH_TOKEN_KEY = 'herabs.authToken';
export const CART_TOKEN_KEY = 'herabs.cartToken';

/** Typed API client: sends the bearer token and guest cart token when we have them. */
export const api = createApiClient({
  baseUrl: API_URL,
  getAuthToken: () => storage.get(AUTH_TOKEN_KEY),
  getCartToken: () => storage.get(CART_TOKEN_KEY),
});

/** Turn an openapi-fetch error body into something to show the user. */
export function errorMessage(error: unknown, fallback = 'Something went wrong. Please try again.'): string {
  if (error && typeof error === 'object' && 'message' in error) {
    const body = error as Partial<ValidationErrors>;
    const first = body.errors && Object.values(body.errors)[0]?.[0];
    return first || body.message || fallback;
  }
  return fallback;
}

/** Throw on API errors so TanStack Query treats them as failures. */
export function unwrap<T>(result: { data?: T; error?: unknown; response: Response }): T {
  if (result.error !== undefined || result.data === undefined) {
    throw new ApiError(result.response.status, errorMessage(result.error));
  }
  return result.data;
}

export class ApiError extends Error {
  constructor(
    public status: number,
    message: string,
  ) {
    super(message);
  }
}
