import type { Metadata } from "next";
import Link from "next/link";
import { BasketCard } from "@/components/basket/BasketCard";
import { Breadcrumb } from "@/components/Breadcrumb";
import { getBaskets } from "@/lib/api";

export const metadata: Metadata = {
  title: "Grocery baskets",
  description: "Ready-made grocery baskets at one price. Delivered across Nairobi; pay on delivery.",
  alternates: { canonical: "/baskets" },
};

export default async function BasketsPage() {
  const baskets = await getBaskets();

  return (
    <>
      <Breadcrumb items={[{ name: "Baskets", path: "/baskets" }]} />
      <section className="shop--area pt-90 pb-90">
        <div className="container">
          <h1 className="h3 mb-10">Grocery baskets</h1>
          <p className="mb-30">Ready-made bundles at one price. Add a basket to your cart like any product.</p>
          {baskets.length === 0 ? (
            <p>No baskets are available right now. <Link href="/shop">Browse all products</Link>.</p>
          ) : (
            <div className="shop-products-wrap">
              <div className="row">
                {baskets.map((basket, i) => (
                  <div key={basket.id} className="col-xl-3 col-md-4 col-6">
                    <BasketCard basket={basket} eager={i < 4} />
                  </div>
                ))}
              </div>
            </div>
          )}
        </div>
      </section>
    </>
  );
}
