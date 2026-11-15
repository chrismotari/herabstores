import type { Metadata } from "next";
import { Breadcrumb } from "@/components/Breadcrumb";
import { ProductListing } from "@/components/shop/ProductListing";
import { ShopLayout } from "@/components/shop/ShopLayout";

export const metadata: Metadata = {
  title: "Shop all groceries",
  description: "Browse fresh fruit, vegetables and groceries. Delivered across Nairobi; pay on delivery.",
  alternates: { canonical: "/shop" },
};

export default function ShopPage(props: PageProps<"/shop">) {
  return (
    <>
      <Breadcrumb items={[{ name: "Shop", path: "/shop" }]} />
      <ShopLayout intro={<h1 className="h3 mb-30">All products</h1>}>
        <ProductListing basePath="/shop" params={props.searchParams.then(pickListingParams)} />
      </ShopLayout>
    </>
  );
}

function pickListingParams(params: Record<string, string | string[] | undefined>) {
  const one = (v: string | string[] | undefined) => (Array.isArray(v) ? v[0] : v);
  return { q: one(params.q), sort: one(params.sort), page: one(params.page), in_stock: one(params.in_stock), category: one(params.category) };
}
