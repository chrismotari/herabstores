import Image from "next/image";
import Link from "next/link";
import { getCategories } from "@/lib/api";
import { CONTACT } from "@/lib/config";
import { HeaderCart, HeaderAccount } from "./HeaderWidgets";

export async function HeaderSearch() {
  const categories = await getCategories();

  return (
    <div className="header-search-area">
      <div className="container custom-container">
        <div className="row align-items-center">
          <div className="col-xl-2 col-lg-3 d-none d-lg-block">
            <div className="logo">
              <Link href="/">
                <Image src="/theme/img/logo/logo.png" alt="Herab Groceries" width={187} height={96} loading="eager" />
              </Link>
            </div>
          </div>
          <div className="col-xl-10 col-lg-9">
            <div className="d-block d-sm-flex align-items-center justify-content-end">
              <div className="header-search-wrap">
                {/* Plain GET form: works without JS and lands on a crawlable /shop URL */}
                <form action="/shop" method="get" role="search">
                  <select className="custom-select" name="category" aria-label="Category" defaultValue="">
                    <option value="">All Categories</option>
                    {categories.map((category) => (
                      <option key={category.id} value={category.slug}>{category.name}</option>
                    ))}
                  </select>
                  <input type="text" placeholder="Search Product..." name="q" aria-label="Search products" />
                  <button type="submit" aria-label="Search"><i className="flaticon-loupe-1" /></button>
                </form>
              </div>
              <div className="header-action">
                <ul>
                  <li className="header-phone">
                    <div className="icon"><i className="flaticon-telephone" /></div>
                    <a href={CONTACT.phoneHref}><span>Call Us Now</span>{CONTACT.phone}</a>
                  </li>
                  <HeaderAccount />
                  <HeaderCart />
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}
