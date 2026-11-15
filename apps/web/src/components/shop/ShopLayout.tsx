import { Suspense, type ReactNode } from "react";
import { ShopSidebar } from "./ShopSidebar";

export function ShopLayout({ activeCategory, intro, children }: { activeCategory?: string; intro?: ReactNode; children: ReactNode }) {
  return (
    <section className="shop--area pt-90 pb-90">
      <div className="container">
        <div className="row justify-content-center">
          <div className="col-lg-3 order-2 order-lg-0">
            <Suspense>
              <ShopSidebar activeCategory={activeCategory} />
            </Suspense>
          </div>
          <div className="col-lg-9">
            {intro}
            <Suspense fallback={<p>Loading products...</p>}>{children}</Suspense>
          </div>
        </div>
      </div>
    </section>
  );
}
