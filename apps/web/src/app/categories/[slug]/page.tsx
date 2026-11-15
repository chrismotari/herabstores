import type { Metadata } from "next";
import { notFound } from "next/navigation";
import { Breadcrumb } from "@/components/Breadcrumb";
import { ProductListing } from "@/components/shop/ProductListing";
import { ShopLayout } from "@/components/shop/ShopLayout";
import { getCategories, getCategory } from "@/lib/api";
import { describe } from "@/lib/seo";

export async function generateStaticParams() {
  const categories = await getCategories();
  // Cache Components needs at least one entry; the placeholder renders a 404.
  return categories.length ? categories.map((c) => ({ slug: c.slug })) : [{ slug: "__none__" }];
}

export async function generateMetadata(props: PageProps<"/categories/[slug]">): Promise<Metadata> {
  const { slug } = await props.params;
  const category = await getCategory(slug);
  if (!category) return {};

  return {
    title: category.name,
    description: describe(category.description, `Shop ${category.name} online. Delivered across Nairobi; pay on delivery.`),
    alternates: { canonical: `/categories/${category.slug}` },
    openGraph: { url: `/categories/${category.slug}`, images: category.image_url ?? undefined },
  };
}

export default async function CategoryPage(props: PageProps<"/categories/[slug]">) {
  const { slug } = await props.params;
  const category = await getCategory(slug);
  if (!category) notFound();

  return (
    <>
      <Breadcrumb items={[{ name: "Shop", path: "/shop" }, { name: category.name, path: `/categories/${category.slug}` }]} />
      <ShopLayout
        activeCategory={category.slug}
        intro={
          <div className="mb-30">
            <h1 className="h3">{category.name}</h1>
            {category.description && category.description !== category.name && <p>{category.description}</p>}
          </div>
        }
      >
        <ProductListing basePath={`/categories/${category.slug}`} category={category.slug} params={props.searchParams} />
      </ShopLayout>
    </>
  );
}
