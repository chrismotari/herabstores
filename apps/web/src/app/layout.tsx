import type { Metadata } from "next";
import { Suspense } from "react";
import { JsonLd } from "@/components/JsonLd";
import { SessionProvider } from "@/components/auth/SessionProvider";
import { CartProvider } from "@/components/cart/CartProvider";
import { Footer } from "@/components/layout/Footer";
import { HeaderSearch } from "@/components/layout/HeaderSearch";
import { HeaderTop } from "@/components/layout/HeaderTop";
import { Navbar } from "@/components/layout/Navbar";
import { ScrollTop } from "@/components/layout/NavbarClient";
import { SITE_NAME, SITE_URL } from "@/lib/config";
import { organizationJsonLd } from "@/lib/seo";
import "./globals.css";

export const metadata: Metadata = {
  metadataBase: new URL(SITE_URL),
  title: { default: `${SITE_NAME} - Fresh Groceries Delivered in Nairobi`, template: `%s | ${SITE_NAME}` },
  description: "Order fresh fruit, vegetables and groceries online. Delivered to your door in Nairobi; pay on delivery.",
  openGraph: { siteName: SITE_NAME, type: "website", locale: "en_KE", images: "/theme/img/logo/logo.png" },
  icons: { icon: "/theme/img/favicon.png" },
};

// The theme's stylesheets, in their original order (served from /public/theme).
const THEME_CSS = ["bootstrap.min", "fontawesome-all.min", "flaticon", "default", "style", "responsive"];

export default function RootLayout({ children }: Readonly<{ children: React.ReactNode }>) {
  return (
    <html lang="en">
      <body>
        {THEME_CSS.map((name) => (
          <link key={name} rel="stylesheet" href={`/theme/css/${name}.css`} precedence="theme" />
        ))}
        <JsonLd data={organizationJsonLd()} />
        <CartProvider>
        <SessionProvider>
        <ScrollTop />
        <header>
          <HeaderTop />
          <Suspense fallback={<div className="header-search-area" />}>
            <HeaderSearch />
          </Suspense>
          <Suspense fallback={<div id="sticky-header" className="menu-area" />}>
            <Navbar />
          </Suspense>
        </header>
        <main>{children}</main>
        <Footer />
        </SessionProvider>
        </CartProvider>
      </body>
    </html>
  );
}
