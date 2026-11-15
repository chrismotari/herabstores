/**
 * Runtime configuration.
 *
 * - API_URL: base URL the Next.js server uses to reach the API (can be internal).
 * - NEXT_PUBLIC_API_URL: base URL browsers use (cart, account, checkout).
 * - NEXT_PUBLIC_SITE_URL: public storefront origin, for canonical URLs, sitemap and OpenGraph.
 */
export const SITE_URL = (process.env.NEXT_PUBLIC_SITE_URL || "http://localhost:3000").replace(/\/$/, "");

export const PUBLIC_API_URL = (process.env.NEXT_PUBLIC_API_URL || "http://localhost:8000/api/v1").replace(/\/$/, "");

/** Empty during CI builds with no API: catalog data is then fetched at request time. */
export const SERVER_API_URL = (process.env.API_URL ?? PUBLIC_API_URL).replace(/\/$/, "");

export const SITE_NAME = "Herab Groceries";

export const CONTACT = {
  phone: "+254 789 45 42 42",
  phoneHref: "tel:+254789454242",
  email: "info@herabgroceries.com",
  address: "Westfield Mbaazi, Lavington, Nairobi",
  hours: "Sun - Fri : 8:00 to 21:00",
};
