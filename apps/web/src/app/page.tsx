import type { Metadata } from "next";
import Image from "next/image";
import Link from "next/link";
import { preload } from "react-dom";
import { ProductGrid } from "@/components/product/ProductCard";
import { getCategories, getProducts } from "@/lib/api";

export const metadata: Metadata = { alternates: { canonical: "/" } };

const CATEGORY_FALLBACK_IMAGES = [
  "/theme/img/product/category_img01.png",
  "/theme/img/product/category_img02.png",
  "/theme/img/product/category_img03.png",
  "/theme/img/product/watermelon.png",
  "/theme/img/product/category_img06.png",
];

const PROMOS = [
  { image: "/theme/img/product/discount_img01.jpg", kicker: "fresh daily", title: "Fruit & vegetables", href: "/shop" },
  { image: "/theme/img/product/discount_img02.jpg", kicker: "healthy food", title: "Hygienically packed", href: "/shop" },
  { image: "/theme/img/product/discount_img03.jpg", kicker: "no card needed", title: "Pay on delivery", href: "/faq", style: "style-two" },
];

export default async function Home() {
  // The hero is a CSS background, which browsers discover late; fetch it up front (LCP).
  preload("/theme/img/bg/slider_area_bg.webp", { as: "image", fetchPriority: "high" });
  preload("/theme/img/slider/slider_bg01.webp", { as: "image" });
  const [categories, latest] = await Promise.all([getCategories(), getProducts({ per_page: 8 })]);

  return (
    <>
      <section className="slider-area" style={{ backgroundImage: "url(/theme/img/bg/slider_area_bg.webp)" }}>
        <div className="container custom-container">
          <div className="row">
            <div className="col-12">
              <div className="single-slider slider-bg" style={{ backgroundImage: "url(/theme/img/slider/slider_bg01.webp)" }}>
                <div className="slider-content">
                  <h5 className="sub-title">fresh &amp; local</h5>
                  <h1 className="title">Groceries delivered</h1>
                  <p>Fresh produce and pantry staples, delivered across Nairobi. Pay on delivery.</p>
                  <Link href="/shop" className="btn rounded-btn">Shop Now</Link>
                </div>
              </div>
            </div>
          </div>
        </div>

        {categories.length > 0 && (
          <div className="container custom-container">
            <div className="slider-category-wrap">
              <div className="row category-active">
                {categories.slice(0, 6).map((category, i) => (
                  <div key={category.id} className="col-lg-2 col-md-4 col-6">
                    <div className={`category-item${i === 0 ? " active" : ""}`}>
                      <Link href={`/categories/${category.slug}`} className="category-link" aria-label={category.name} />
                      <div className="category-thumb">
                        <Image
                          src={category.image_url || CATEGORY_FALLBACK_IMAGES[i % CATEGORY_FALLBACK_IMAGES.length]}
                          alt=""
                          width={120}
                          height={120}
                        />
                      </div>
                      <div className="category-content">
                        <h2 className="title h6">{category.name}</h2>
                      </div>
                    </div>
                  </div>
                ))}
              </div>
            </div>
          </div>
        )}
      </section>

      <section className="discount-area pt-80">
        <div className="container">
          <div className="row justify-content-center">
            {PROMOS.map((promo) => (
              <div key={promo.title} className="col-xl-4 col-lg-6 col-md-8">
                <div className={`discount-item mb-20${promo.style ? ` ${promo.style}` : ""}`}>
                  <div className="discount-thumb">
                    <Image src={promo.image} alt="" width={400} height={220} style={{ width: "100%", height: "auto" }} />
                  </div>
                  <div className="discount-content">
                    <span>{promo.kicker}</span>
                    <h3 className="title h4"><Link href={promo.href}>{promo.title}</Link></h3>
                    <Link href={promo.href} className="btn">shop now</Link>
                  </div>
                </div>
              </div>
            ))}
          </div>
        </div>
      </section>

      <section className="shop--area pt-60 pb-90">
        <div className="container">
          <div className="section-title text-center mb-40">
            <h2 className="title">Fresh in store</h2>
          </div>
          <div className="shop-products-wrap">
            <ProductGrid products={latest.data} />
          </div>
          <div className="text-center mt-30">
            <Link href="/shop" className="btn rounded-btn">See all products</Link>
          </div>
        </div>
      </section>
    </>
  );
}
