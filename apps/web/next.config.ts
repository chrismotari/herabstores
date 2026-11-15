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
};

export default nextConfig;
