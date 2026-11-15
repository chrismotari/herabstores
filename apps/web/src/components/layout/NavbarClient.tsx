"use client";

import Image from "next/image";
import Link from "next/link";
import { usePathname } from "next/navigation";
import { useEffect, useState, type ReactNode } from "react";

/** Replaces the theme's jQuery: sticky header after scrolling past the top bars. */
export function StickyHeader({ children }: { children: ReactNode }) {
  const [sticky, setSticky] = useState(false);

  useEffect(() => {
    const onScroll = () => setSticky(window.scrollY > 245);
    onScroll();
    window.addEventListener("scroll", onScroll, { passive: true });
    return () => window.removeEventListener("scroll", onScroll);
  }, []);

  return (
    <div id="sticky-header" className={`menu-area${sticky ? " sticky-menu" : ""}`}>
      {children}
    </div>
  );
}

export function CategoryMenu({ children }: { children: ReactNode }) {
  const [open, setOpen] = useState(false);

  return (
    <>
      <button type="button" className="cat-toggle" aria-expanded={open} onClick={() => setOpen(!open)}>
        <i className="fas fa-bars" />Shop by Category<i className="fas fa-angle-down" />
      </button>
      {/* Close after picking a category (clicks bubble up from the links) */}
      <ul className="category-menu" style={{ display: open ? "block" : undefined }} onClick={() => setOpen(false)}>
        {children}
      </ul>
    </>
  );
}

const BODY_CLASS = "mobile-menu-visible";

export function MobileMenuToggle() {
  return (
    <button
      type="button"
      className="mobile-nav-toggler"
      aria-label="Open menu"
      onClick={() => document.body.classList.add(BODY_CLASS)}
    >
      <i className="fas fa-bars" />
    </button>
  );
}

export function MobileMenu({
  items,
  categories,
}: {
  items: { href: string; label: string }[];
  categories: { name: string; slug: string }[];
}) {
  const pathname = usePathname();
  const close = () => document.body.classList.remove(BODY_CLASS);
  useEffect(close, [pathname]);

  return (
    <>
      <div className="mobile-menu">
        <nav className="menu-box" aria-label="Mobile">
          <button type="button" className="close-btn" aria-label="Close menu" onClick={close}>
            <i className="fas fa-times" />
          </button>
          <div className="nav-logo">
            <Link href="/"><Image src="/theme/img/logo/logo.png" alt="Herab Groceries" width={177} height={45} /></Link>
          </div>
          <div className="menu-outer">
            <ul className="navigation">
              {items.map((item) => (
                <li key={item.href}><Link href={item.href}>{item.label}</Link></li>
              ))}
              {categories.map((category) => (
                <li key={category.slug}><Link href={`/categories/${category.slug}`}>{category.name}</Link></li>
              ))}
            </ul>
          </div>
        </nav>
      </div>
      <div className="menu-backdrop" onClick={close} />
    </>
  );
}

/** Theme's back-to-top button. */
export function ScrollTop() {
  const [visible, setVisible] = useState(false);

  useEffect(() => {
    const onScroll = () => setVisible(window.scrollY > 245);
    window.addEventListener("scroll", onScroll, { passive: true });
    return () => window.removeEventListener("scroll", onScroll);
  }, []);

  return (
    <button
      type="button"
      className="scroll-top scroll-to-target"
      aria-label="Back to top"
      style={{ display: visible ? "block" : "none" }}
      onClick={() => window.scrollTo({ top: 0, behavior: "smooth" })}
    >
      <i className="fas fa-angle-up" />
    </button>
  );
}
