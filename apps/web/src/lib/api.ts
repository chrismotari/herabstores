import "server-only";

import { createApiClient, type Category } from "@herabs/shared";
import { cacheLife, cacheTag } from "next/cache";
import { SERVER_API_URL } from "./config";

/**
 * Server-side API access for public catalog data.
 *
 * Each loader is a `'use cache'` function tagged to match the API's revalidation
 * webhook (see spec 3.15): `categories`, `products`, `product:{slug}`, ...
 * Cached for a long time; the webhook refreshes them when admins edit the catalog.
 */
export const api = createApiClient({ baseUrl: SERVER_API_URL || "http://invalid.local" });

const apiAvailable = () => SERVER_API_URL !== "";

export async function getCategories(): Promise<Category[]> {
  "use cache";
  cacheTag("categories");
  cacheLife("max");
  if (!apiAvailable()) return []; // CI build without an API

  const { data, error } = await api.GET("/categories");
  if (error || !data) throw new Error("Failed to load categories");

  return data.data;
}
