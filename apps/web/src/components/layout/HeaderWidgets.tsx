"use client";

import Link from "next/link";
import { useCart } from "@/components/cart/CartProvider";

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

/** Cart icon with live item count. The mini cart dropdown comes in spec 5.7. */
export function HeaderCart() {
  const { count } = useCart();

  return (
    <li className="header-cart-action">
      <div className="header-cart-wrap">
        <Link href="/cart" aria-label="Cart"><i className="flaticon-shopping-basket" /></Link>
        <span className="item-count">{count}</span>
      </div>
    </li>
  );
}
