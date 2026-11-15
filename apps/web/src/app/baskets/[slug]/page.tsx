import { formatPrice } from "@herabs/shared";
import type { Metadata } from "next";
import Image from "next/image";
import Link from "next/link";
import { notFound } from "next/navigation";
import { Breadcrumb } from "@/components/Breadcrumb";
import { AddToCart } from "@/components/cart/AddToCart";
import { getBasket, getBaskets } from "@/lib/api";
import { describe } from "@/lib/seo";

const PLACEHOLDER = "/theme/img/product/discount_img01.jpg";

// Unknown or expired baskets return a real 404 (see the product page).
export const ensureStatic = "navigation";

export async function generateStaticParams() {
  const baskets = await getBaskets();
  return baskets.length ? baskets.map((b) => ({ slug: b.slug ?? "" })).filter((p) => p.slug) : [{ slug: "__none__" }];
}

export async function generateMetadata(props: PageProps<"/baskets/[slug]">): Promise<Metadata> {
  const { slug } = await props.params;
  const basket = await getBasket(slug);
  if (!basket) return {};

  return {
    title: basket.name,
    description: describe(basket.description, `${basket.name}: a ready-made grocery basket for ${formatPrice(basket.price)}.`),
    alternates: { canonical: `/baskets/${basket.slug}` },
    openGraph: { url: `/baskets/${basket.slug}`, images: basket.image_url ?? undefined },
  };
}

export default async function BasketPage(props: PageProps<"/baskets/[slug]">) {
  const { slug } = await props.params;
  const basket = await getBasket(slug);
  if (!basket) notFound();

  const items = basket.items ?? [];
  const until = basket.available_until ? new Date(basket.available_until).toLocaleDateString("en-KE", { day: "numeric", month: "long" }) : null;

  return (
    <>
      <Breadcrumb items={[{ name: "Baskets", path: "/baskets" }, { name: basket.name, path: `/baskets/${basket.slug}` }]} />
      <section className="shop-details-area pt-90 pb-90">
        <div className="container">
          <div className="row">
            <div className="col-lg-6 mb-40">
              <Image
                src={basket.image_url || PLACEHOLDER}
                alt={basket.name}
                width={600}
                height={600}
                loading="eager"
                fetchPriority="high"
                sizes="(min-width: 992px) 50vw, 100vw"
                style={{ width: "100%", height: "auto", objectFit: "cover" }}
              />
            </div>
            <div className="col-lg-6">
              <div className="shop-details-content">
                <h1 className="title h4">{basket.name}</h1>
                <div className="shop-details-price">
                  <h2 className="price">{formatPrice(basket.price)}</h2>
                  <h3 className="stock-status h5">- {basket.in_stock ? "Available" : "Sold out"}</h3>
                </div>
                <p style={{ whiteSpace: "pre-line" }}>{basket.description}</p>
                {until && <p className="small">Available until {until}.</p>}

                {items.length > 0 && (
                  <>
                    <h2 className="h5 mt-30">What&apos;s inside</h2>
                    <ul className="mb-30">
                      {items.map((item) => (
                        <li key={item.product.id}>
                          {item.quantity} x <Link href={`/products/${item.product.slug}`}>{item.product.name}</Link>
                        </li>
                      ))}
                    </ul>
                  </>
                )}

                <AddToCart basketId={basket.id} maxQuantity={basket.available_quantity} variant="detail" />
              </div>
            </div>
          </div>
        </div>
      </section>
    </>
  );
}
