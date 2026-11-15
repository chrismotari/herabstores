"use client";

import Link from "next/link";

/** Account icon. Becomes session-aware in spec 5.8. */
export function HeaderAccount() {
  return (
    <li className="header-cart-action">
      <div className="header-cart-wrap">
        <Link href="/account" aria-label="My account"><i className="flaticon-user" /></Link>
      </div>
    </li>
  );
}

/** Cart icon. Shows the live count and mini cart in spec 5.7. */
export function HeaderCart() {
  return (
    <li className="header-cart-action">
      <div className="header-cart-wrap">
        <Link href="/cart" aria-label="Cart"><i className="flaticon-shopping-basket" /></Link>
        <span className="item-count">0</span>
      </div>
    </li>
  );
}
