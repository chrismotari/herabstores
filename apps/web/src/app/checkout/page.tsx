import type { Metadata } from "next";
import { Suspense } from "react";
import { Breadcrumb } from "@/components/Breadcrumb";
import { getDeliveryZones } from "@/lib/api";
import { CheckoutForm } from "./CheckoutForm";

export const metadata: Metadata = { title: "Checkout", robots: { index: false, follow: false } };

export default function CheckoutPage() {
  return (
    <>
      <Breadcrumb items={[{ name: "Cart", path: "/cart" }, { name: "Checkout", path: "/checkout" }]} />
      <section className="checkout-area pt-90 pb-90 shop-cart-widget">
        <div className="container">
          <Suspense fallback={<p>Loading checkout...</p>}>
            <Checkout />
          </Suspense>
        </div>
      </section>
    </>
  );
}

async function Checkout() {
  const zones = await getDeliveryZones();
  return <CheckoutForm zones={zones} />;
}
