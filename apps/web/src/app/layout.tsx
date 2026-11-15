import type { Metadata } from "next";
import { Architects_Daughter, Nunito, Poppins } from "next/font/google";
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
// The theme CSS (one combined file) first, then our tweaks. Order matters.
import "../../public/theme/css/theme.css";
import "./globals.css";

export const metadata: Metadata = {
  metadataBase: new URL(SITE_URL),
  title: { default: `${SITE_NAME} - Fresh Groceries Delivered in Nairobi`, template: `%s | ${SITE_NAME}` },
  description: "Order fresh fruit, vegetables and groceries online. Delivered to your door in Nairobi; pay on delivery.",
  openGraph: { siteName: SITE_NAME, type: "website", locale: "en_KE", images: "/theme/img/logo/logo.png" },
  icons: { icon: "/theme/img/favicon.png" },
};

// Self-hosted theme fonts (were a render-blocking Google Fonts @import in the theme CSS).
const body = Poppins({ subsets: ["latin"], weight: ["400", "500", "600", "700"], variable: "--font-poppins", display: "swap" });
const heading = Nunito({ subsets: ["latin"], weight: ["400", "600", "700", "800", "900"], variable: "--font-nunito", display: "swap" });
const script = Architects_Daughter({ subsets: ["latin"], weight: "400", variable: "--font-architects", display: "swap" });

export default function RootLayout({ children }: Readonly<{ children: React.ReactNode }>) {
  return (
    <html lang="en" className={`${body.variable} ${heading.variable} ${script.variable}`}>
      <body>
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
