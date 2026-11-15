import "server-only";

import { createApiClient, type Basket, type Category, type DeliveryZone, type Product } from "@herabs/shared";
import { cacheLife, cacheTag } from "next/cache";
import { SERVER_API_URL } from "./config";

/**
 * Server-side API access for public catalog data.
 *
 * Each loader is a `'use cache'` function tagged to match the API's revalidation
 * webhook (see spec 3.15): `categories`, `products`, `product:{slug}`, ...
 * Cached for a long time; the webhook refreshes them when admins edit the catalog.
 */
export const api = createApiClient({
  baseUrl: SERVER_API_URL || "http://invalid.local",
  // Lets the API exempt this server from per-IP rate limits (prerendering many pages at once).
  headers: process.env.API_SERVER_KEY ? { "X-Storefront-Key": process.env.API_SERVER_KEY } : undefined,
});

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

export type SitemapFeed = {
  products: { slug: string; updated_at: string | null }[];
  categories: { slug: string; updated_at: string | null }[];
  baskets: { slug: string; updated_at: string | null }[];
};

export async function getSitemapFeed(): Promise<SitemapFeed> {
  "use cache";
  cacheTag("products", "categories", "baskets");
  cacheLife("max");
  if (!apiAvailable()) return { products: [], categories: [], baskets: [] };

  const { data, error } = await api.GET("/seo/sitemap");
  if (error || !data) throw new Error("Failed to load the sitemap feed");

  return data.data as SitemapFeed;
}

export type ProductQuery = {
  q?: string;
  category?: string;
  sort?: "newest" | "price_asc" | "price_desc" | "name";
  min_price?: number;
  max_price?: number;
  in_stock?: boolean;
  page?: number;
  per_page?: number;
};

export type ProductPage = {
  data: Product[];
  meta: { current_page: number; last_page: number; total: number; per_page: number; from: number | null; to: number | null };
};

const EMPTY_PAGE: ProductPage = { data: [], meta: { current_page: 1, last_page: 1, total: 0, per_page: 24, from: null, to: null } };

/** A page of available products. Arguments are part of the cache key. */
export async function getProducts(query: ProductQuery = {}): Promise<ProductPage> {
  "use cache";
  cacheTag("products");
  if (query.category) cacheTag(`category:${query.category}`);
  cacheLife("max");
  if (!apiAvailable()) return EMPTY_PAGE;

  const { data, error, response } = await api.GET("/products", {
    params: { query: { ...query, in_stock: query.in_stock ? true : undefined } as never },
  });
  if (response?.status === 422) return EMPTY_PAGE; // invalid filters from the URL
  if (error || !data) throw new Error("Failed to load products");

  const { meta } = data as unknown as ProductPage;
  return { data: data.data, meta };
}

/** One available product, or null when it doesn't exist / is hidden. */
export async function getProduct(slug: string): Promise<Product | null> {
  "use cache";
  cacheTag("products", `product:${slug}`);
  cacheLife("max");
  if (!apiAvailable()) return null;

  const { data, response } = await api.GET("/products/{slug}", { params: { path: { slug } } });
  if (response.status === 404) return null;
  if (!data) throw new Error(`Failed to load product ${slug} (HTTP ${response.status})`);

  return data.data;
}

export async function getCategory(slug: string): Promise<Category | null> {
  "use cache";
  cacheTag("categories", `category:${slug}`);
  cacheLife("max");
  if (!apiAvailable()) return null;

  const { data, response } = await api.GET("/categories/{slug}", { params: { path: { slug } } });
  if (response.status === 404) return null;
  if (!data) throw new Error(`Failed to load category ${slug} (HTTP ${response.status})`);

  return data.data;
}

/** Active delivery zones with fees (not covered by the catalog webhook, so refreshed hourly). */
export async function getDeliveryZones(): Promise<DeliveryZone[]> {
  "use cache";
  cacheTag("delivery-zones");
  cacheLife("hours");
  if (!apiAvailable()) return [];

  const { data, error } = await api.GET("/delivery-zones");
  if (error || !data) throw new Error("Failed to load delivery zones");

  return data.data;
}

/** Baskets available now (active, inside their availability window). */
export async function getBaskets(): Promise<Basket[]> {
  "use cache";
  cacheTag("baskets");
  // Availability windows open and close on a schedule, so don't cache for days.
  cacheLife("hours");
  if (!apiAvailable()) return [];

  const { data, error } = await api.GET("/baskets");
  if (error || !data) throw new Error("Failed to load baskets");

  return data.data;
}

export async function getBasket(slug: string): Promise<Basket | null> {
  "use cache";
  cacheTag("baskets", `basket:${slug}`);
  cacheLife("hours");
  if (!apiAvailable()) return null;

  const { data, response } = await api.GET("/baskets/{slug}", { params: { path: { slug } } });
  if (response.status === 404) return null;
  if (!data) throw new Error(`Failed to load basket ${slug} (HTTP ${response.status})`);

  return data.data;
}
