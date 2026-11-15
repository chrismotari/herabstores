import Image from "next/image";
import Link from "next/link";
import { getCategories } from "@/lib/api";
import { CategoryMenu, MobileMenu, MobileMenuToggle, StickyHeader } from "./NavbarClient";

export const MAIN_NAV = [
  { href: "/", label: "Home" },
  { href: "/shop", label: "Shop" },
  { href: "/account/orders", label: "My Orders" },
  { href: "/about-us", label: "About Us" },
  { href: "/contact-us", label: "Contact" },
];

export async function Navbar() {
  const categories = await getCategories();

  return (
    <StickyHeader>
      <div className="container custom-container">
        <div className="row">
          <div className="col-12">
            <MobileMenuToggle />
            <div className="menu-wrap">
              <nav className="menu-nav" aria-label="Main">
                <div className="logo d-block d-lg-none">
                  <Link href="/"><Image src="/theme/img/logo/logo.png" alt="Herab Groceries" width={177} height={45} /></Link>
                </div>
                <div className="header-category d-none d-lg-block">
                  <CategoryMenu>
                    {categories.map((category) => (
                      <li key={category.id}>
                        <Link href={`/categories/${category.slug}`}><i className="flaticon-nut" /> {category.name}</Link>
                      </li>
                    ))}
                    <li><Link href="/shop"><i className="flaticon-shop" /> All products</Link></li>
                  </CategoryMenu>
                </div>
                <div className="navbar-wrap main-menu d-none d-lg-flex">
                  <ul className="navigation">
                    {MAIN_NAV.map((item) => (
                      <li key={item.href}><Link href={item.href}>{item.label}</Link></li>
                    ))}
                  </ul>
                </div>
                <div className="header-super-store d-none d-xl-block d-lg-none d-md-block">
                  <div className="dropdown">
                    <Link className="dropdown-toggle" href="/shop"><i className="flaticon-shop" /> Shop Now</Link>
                  </div>
                </div>
              </nav>
            </div>
            <MobileMenu items={MAIN_NAV} categories={categories.map(({ name, slug }) => ({ name, slug }))} />
          </div>
        </div>
      </div>
    </StickyHeader>
  );
}
