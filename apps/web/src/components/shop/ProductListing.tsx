import { ProductGrid } from "@/components/product/ProductCard";
import { Pagination } from "@/components/Pagination";
import { getProducts, type ProductQuery } from "@/lib/api";
import { SortSelect } from "./SortSelect";

export type ListingParams = { q?: string; sort?: string; page?: string; in_stock?: string; category?: string };

const SORTS = ["newest", "price_asc", "price_desc", "name"] as const;

/** Results for /shop and /categories/[slug]; reads URL params, so render it inside <Suspense>. */
export async function ProductListing({ basePath, category, params }: { basePath: string; category?: string; params: Promise<ListingParams> }) {
  const { q, sort, page, in_stock, category: categoryParam } = await params;
  const query: ProductQuery = {
    q: q?.trim() || undefined,
    category: category ?? (categoryParam || undefined),
    sort: SORTS.includes(sort as (typeof SORTS)[number]) ? (sort as ProductQuery["sort"]) : undefined,
    page: Math.max(1, Number(page) || 1),
    in_stock: in_stock === "1" || undefined,
    per_page: 24,
  };
  const { data, meta } = await getProducts(query);

  const hrefFor = (p: number) => {
    const search = new URLSearchParams();
    if (query.q) search.set("q", query.q);
    if (query.sort) search.set("sort", query.sort);
    if (query.in_stock) search.set("in_stock", "1");
    if (!category && query.category) search.set("category", query.category);
    if (p > 1) search.set("page", String(p));
    const qs = search.toString();
    return qs ? `${basePath}?${qs}` : basePath;
  };

  // Search/sort/filter variants shouldn't compete with /shop and category pages in search results.
  // React hoists this <meta> into <head>.
  const isVariant = Boolean(query.q || query.sort || query.in_stock || (!category && query.category) || query.page! > 1);

  return (
    <>
      {isVariant && <meta name="robots" content="noindex, follow" />}
      <div className="shop-top-meta mb-30">
        <div className="row">
          <div className="col-md-6 col-sm-7">
            <div className="shop-top-left">
              <ul>
                <li>
                  {meta.total === 0
                    ? "No results"
                    : `Showing ${meta.from}–${meta.to} of ${meta.total} result${meta.total === 1 ? "" : "s"}`}
                  {query.q && <> for &ldquo;{query.q}&rdquo;</>}
                </li>
              </ul>
            </div>
          </div>
          <div className="col-md-6 col-sm-5">
            <div className="shop-top-right">
              <SortSelect current={query.sort ?? "newest"} />
            </div>
          </div>
        </div>
      </div>
      <div className="shop-products-wrap">
        <ProductGrid products={data} />
      </div>
      <Pagination page={meta.current_page} lastPage={meta.last_page} hrefFor={hrefFor} />
    </>
  );
}
