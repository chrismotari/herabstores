import { timingSafeEqual } from "node:crypto";
import { revalidateTag } from "next/cache";
import type { NextRequest } from "next/server";

/**
 * On-demand revalidation, called by the API when admins change the catalog
 * (api: App\Jobs\RevalidateStorefront).
 *
 *   POST /api/revalidate
 *   Authorization: Bearer {REVALIDATE_SECRET}
 *   {"tags": ["products", "product:{slug}", "category:{slug}", ...]}
 */
const TAG = /^(products|categories|baskets|delivery-zones|(product|category|basket):[a-z0-9-]{1,200})$/;

function authorized(request: NextRequest): boolean {
  const secret = process.env.REVALIDATE_SECRET;
  const given = request.headers.get("authorization")?.replace(/^Bearer\s+/i, "") ?? "";
  if (!secret || !given) return false;

  const a = Buffer.from(given);
  const b = Buffer.from(secret);
  return a.length === b.length && timingSafeEqual(a, b);
}

export async function POST(request: NextRequest) {
  if (!authorized(request)) {
    return Response.json({ message: "Unauthorized" }, { status: 401 });
  }

  const body = (await request.json().catch(() => null)) as { tags?: unknown } | null;
  const tags = Array.isArray(body?.tags) ? body.tags.filter((t): t is string => typeof t === "string") : [];
  const valid = [...new Set(tags)].filter((t) => TAG.test(t)).slice(0, 100);

  if (valid.length === 0) {
    return Response.json({ message: "No valid tags" }, { status: 422 });
  }

  // Stale-while-revalidate: visitors get the cached page while it rebuilds.
  for (const tag of valid) revalidateTag(tag, "max");

  return Response.json({ revalidated: valid });
}
