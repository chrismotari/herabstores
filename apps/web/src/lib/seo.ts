import type { Product } from "@herabs/shared";
import { CONTACT, SITE_NAME, SITE_URL } from "./config";

export const absoluteUrl = (path: string) => `${SITE_URL}${path.startsWith("/") ? path : `/${path}`}`;

/** Plain-text description for meta tags (max ~160 chars). */
export function describe(text: string | null | undefined, fallback: string): string {
  const clean = (text ?? "").replace(/\s+/g, " ").trim();
  if (!clean) return fallback;
  return clean.length > 160 ? `${clean.slice(0, 157).trimEnd()}...` : clean;
}

export function organizationJsonLd() {
  return {
    "@context": "https://schema.org",
    "@type": "GroceryStore",
    name: SITE_NAME,
    url: SITE_URL,
    logo: absoluteUrl("/theme/img/logo/logo.png"),
    telephone: CONTACT.phone.replace(/\s/g, ""),
    email: CONTACT.email,
    address: {
      "@type": "PostalAddress",
      streetAddress: "Westfield Mbaazi, Lavington",
      addressLocality: "Nairobi",
      addressCountry: "KE",
    },
    openingHours: "Su-Fr 08:00-21:00",
    paymentAccepted: "Cash on delivery",
    currenciesAccepted: "KES",
  };
}

export function breadcrumbJsonLd(items: { name: string; path: string }[]) {
  return {
    "@context": "https://schema.org",
    "@type": "BreadcrumbList",
    itemListElement: items.map((item, index) => ({
      "@type": "ListItem",
      position: index + 1,
      name: item.name,
      item: absoluteUrl(item.path),
    })),
  };
}

export function productJsonLd(product: Product) {
  const images = [product.image_url, ...(product.images ?? [])].filter(Boolean);

  return {
    "@context": "https://schema.org",
    "@type": "Product",
    name: product.name,
    description: describe(product.description, product.name),
    image: images,
    sku: product.slug,
    category: product.category?.name,
    url: absoluteUrl(`/products/${product.slug}`),
    offers: {
      "@type": "Offer",
      url: absoluteUrl(`/products/${product.slug}`),
      priceCurrency: "KES",
      price: product.price.toFixed(2),
      availability: product.in_stock ? "https://schema.org/InStock" : "https://schema.org/OutOfStock",
      itemCondition: "https://schema.org/NewCondition",
      seller: { "@type": "Organization", name: SITE_NAME },
    },
  };
}
