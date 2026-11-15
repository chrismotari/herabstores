import type { NextConfig } from "next";

const apiOrigin = new URL(process.env.NEXT_PUBLIC_API_URL || "http://localhost:8000/api/v1");

const nextConfig: NextConfig = {
  cacheComponents: true,
  partialPrefetching: true,

  images: {
    remotePatterns: [
      // Uploaded product/category images served by the API (public disk)
      { protocol: apiOrigin.protocol.replace(":", "") as "http" | "https", hostname: apiOrigin.hostname, port: apiOrigin.port, pathname: "/storage/**" },
      // Placeholder image used by the starter catalogue (CatalogSeeder)
      { protocol: "https", hostname: "www.denverfruitier.com", pathname: "/cdn/shop/**" },
    ],
    // Local API in development resolves to 127.0.0.1, which Next 16 blocks by default.
    dangerouslyAllowLocalIP: process.env.NODE_ENV !== "production",
  },

  /** Old Blade storefront URLs (301/308) so existing links and search results keep working. */
  async redirects() {
    return [
      { source: "/index", destination: "/", permanent: true },
      { source: "/home", destination: "/", permanent: true },
      { source: "/carts/show/:cart*", destination: "/cart", permanent: true },
      { source: "/orders/create", destination: "/checkout", permanent: true },
      { source: "/orders/track/:id", destination: "/account/orders/:id", permanent: true },
      { source: "/orders/:id((?!create$)[^/]+)", destination: "/account/orders/:id", permanent: true },
      { source: "/users/:id", destination: "/account", permanent: true },
      { source: "/profile", destination: "/account/profile", permanent: true },
      // Old search used ?search= and ?category_id= (an id); keep the term, drop the id.
      {
        source: "/products/search",
        has: [{ type: "query", key: "search", value: "(?<term>.*)" }],
        destination: "/shop?q=:term",
        permanent: true,
      },
      { source: "/products/search", destination: "/shop", permanent: true },
      { source: "/dashboard", destination: `${apiOrigin.origin}/admin`, permanent: false },
    ];
  },
};

export default nextConfig;
