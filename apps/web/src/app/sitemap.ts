import type { MetadataRoute } from "next";
import { getSitemapFeed } from "@/lib/api";
import { absoluteUrl } from "@/lib/seo";

const STATIC_PAGES = ["/", "/shop", "/about-us", "/contact-us", "/faq", "/terms-and-conditions"];

export default async function sitemap(): Promise<MetadataRoute.Sitemap> {
  const feed = await getSitemapFeed();
  const lastModified = (value: string | null) => (value ? new Date(value) : undefined);

  return [
    ...STATIC_PAGES.map((path) => ({
      url: absoluteUrl(path),
      changeFrequency: path === "/" || path === "/shop" ? ("daily" as const) : ("monthly" as const),
      priority: path === "/" ? 1 : path === "/shop" ? 0.9 : 0.3,
    })),
    ...feed.categories.map((c) => ({ url: absoluteUrl(`/categories/${c.slug}`), lastModified: lastModified(c.updated_at), changeFrequency: "weekly" as const, priority: 0.8 })),
    ...feed.products.map((p) => ({ url: absoluteUrl(`/products/${p.slug}`), lastModified: lastModified(p.updated_at), changeFrequency: "weekly" as const, priority: 0.7 })),
  ];
}
