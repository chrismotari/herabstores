import type { MetadataRoute } from "next";
import { absoluteUrl } from "@/lib/seo";

export default function robots(): MetadataRoute.Robots {
  return {
    rules: {
      userAgent: "*",
      allow: "/",
      // Personal pages: nothing to index
      disallow: ["/cart", "/checkout", "/account", "/login", "/register", "/api/"],
    },
    sitemap: absoluteUrl("/sitemap.xml"),
  };
}
