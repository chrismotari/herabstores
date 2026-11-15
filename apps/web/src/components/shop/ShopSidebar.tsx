import Link from "next/link";
import { getCategories } from "@/lib/api";

export async function ShopSidebar({ activeCategory }: { activeCategory?: string }) {
  const categories = await getCategories();

  return (
    <aside className="shop-sidebar">
      <div className="widget shop-widget">
        <div className="shop-widget-title">
          <h2 className="title h6">Product Categories</h2>
        </div>
        <div className="shop-cat-list">
          <ul>
            <li className={!activeCategory ? "active" : undefined}><Link href="/shop">All products</Link></li>
            {categories.map((category) => (
              <li key={category.id} className={activeCategory === category.slug ? "active" : undefined}>
                <Link href={`/categories/${category.slug}`}>
                  {category.name} <span>{category.products_count ?? ""}</span>
                </Link>
              </li>
            ))}
          </ul>
        </div>
      </div>
    </aside>
  );
}
