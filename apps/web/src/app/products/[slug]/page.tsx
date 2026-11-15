import { formatPrice } from "@herabs/shared";
import type { Metadata } from "next";
import Link from "next/link";
import { notFound, permanentRedirect } from "next/navigation";
import { Breadcrumb } from "@/components/Breadcrumb";
import { AddToCart } from "@/components/cart/AddToCart";
import { JsonLd } from "@/components/JsonLd";
import { FavouriteButton } from "@/components/product/FavouriteButton";
import { getProduct, getSitemapFeed } from "@/lib/api";
import { describe, productJsonLd } from "@/lib/seo";
import { ProductGallery } from "./ProductGallery";

const PLACEHOLDER = "/theme/img/product/category_img01.png";

// Render unlisted slugs fully before responding, so unknown products return a real 404
// (not a streamed 200 + noindex "soft 404").
export const ensureStatic = "navigation";

export async function generateStaticParams() {
  const { products } = await getSitemapFeed();
  // Cache Components needs at least one entry; the placeholder renders a 404.
  return products.length ? products.map((p) => ({ slug: p.slug })) : [{ slug: "__none__" }];
}

export async function generateMetadata(props: PageProps<"/products/[slug]">): Promise<Metadata> {
  const { slug } = await props.params;
  const product = await getProduct(slug);
  if (!product) return {};

  const description = describe(product.description, `Buy ${product.name} online for ${formatPrice(product.price)}. Delivered in Nairobi.`);

  return {
    title: product.name,
    description,
    alternates: { canonical: `/products/${product.slug}` },
    openGraph: {
      type: "website",
      url: `/products/${product.slug}`,
      title: product.name,
      description,
      images: product.image_url ? [{ url: product.image_url, alt: product.name }] : undefined,
    },
  };
}

export default async function ProductPage(props: PageProps<"/products/[slug]">) {
  const { slug } = await props.params;
  const product = await getProduct(slug);
  if (!product) notFound();
  // Old storefront links used the product id: send them to the canonical slug URL.
  if (product.slug !== slug) permanentRedirect(`/products/${product.slug}`);

  const images = [product.image_url, ...(product.images ?? [])].filter((src): src is string => Boolean(src));
  const category = product.category;

  return (
    <>
      <JsonLd data={productJsonLd(product)} />
      <Breadcrumb
        items={[
          { name: "Shop", path: "/shop" },
          ...(category ? [{ name: category.name, path: `/categories/${category.slug}` }] : []),
          { name: product.name, path: `/products/${product.slug}` },
        ]}
      />
      <section className="shop-details-area pt-90 pb-90">
        <div className="container">
          <div className="row">
            <div className="col-lg-7">
              <ProductGallery name={product.name} images={images.length ? [...new Set(images)] : [PLACEHOLDER]} />
            </div>
            <div className="col-lg-5">
              <div className="shop-details-content">
                <h1 className="title h4">{product.name}</h1>
                <div className="shop-details-price">
                  <h2 className="price">{formatPrice(product.price)}</h2>
                  <h3 className="stock-status h5">- {product.stock_status}</h3>
                </div>
                <p style={{ whiteSpace: "pre-line" }}>{product.description}</p>
                {category && (
                  <div className="shop-details-list">
                    <ul>
                      <li>Category : <Link href={`/categories/${category.slug}`}><span>{category.name}</span></Link></li>
                    </ul>
                  </div>
                )}
                <AddToCart productId={product.id} maxQuantity={product.available_quantity} variant="detail" />
                <div className="shop-details-bottom">
                  <FavouriteButton productId={product.id} slug={product.slug} />
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </>
  );
}
