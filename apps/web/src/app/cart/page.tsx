import type { Metadata } from "next";
import { Breadcrumb } from "@/components/Breadcrumb";
import { CartView } from "./CartView";

export const metadata: Metadata = { title: "Your cart", robots: { index: false, follow: true } };

export default function CartPage() {
  return (
    <>
      <Breadcrumb items={[{ name: "Cart", path: "/cart" }]} />
      <section className="cart-area pt-90 pb-90">
        <div className="container">
          <CartView />
        </div>
      </section>
    </>
  );
}
