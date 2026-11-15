"use client";

import { formatPrice } from "@herabs/shared";
import Link from "next/link";
import { CartLineImage, lineHref, lineName } from "@/components/cart/CartLineImage";
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

/** Cart icon with live item count and the theme's hover mini cart. */
export function HeaderCart() {
  const { cart, count, remove } = useCart();
  const items = cart?.items ?? [];

  return (
    <li className="header-cart-action">
      <div className="header-cart-wrap">
        <Link href="/cart" aria-label={`Cart, ${count} item${count === 1 ? "" : "s"}`}><i className="flaticon-shopping-basket" /></Link>
        <span className="item-count">{count}</span>
        <ul className="minicart">
          {items.length === 0 && <li>Your cart is empty.</li>}
          {items.map((item) => (
            <li key={item.id} className="d-flex align-items-start">
              <div className="cart-img">
                <Link href={lineHref(item)}><CartLineImage item={item} size={70} /></Link>
              </div>
              <div className="cart-content">
                <h4><Link href={lineHref(item)}>{item.quantity} x {lineName(item)}</Link></h4>
                <div className="cart-price"><span className="new">{formatPrice(item.unit_price)}</span></div>
              </div>
              <div className="del-icon">
                <button type="button" className="btn-link p-0 border-0 bg-transparent" aria-label={`Remove ${lineName(item)}`} onClick={() => remove(item.id)}>
                  <i className="far fa-trash-alt" />
                </button>
              </div>
            </li>
          ))}
          {items.length > 0 && (
            <>
              <li>
                <div className="total-price">
                  <span className="f-left">Total:</span>
                  <span className="f-right">{formatPrice(cart?.subtotal ?? 0)}</span>
                </div>
              </li>
              <li>
                <div className="checkout-link">
                  <Link href="/cart">Show Cart</Link>
                  <Link className="black-color" href="/checkout">Checkout</Link>
                </div>
              </li>
            </>
          )}
        </ul>
      </div>
      <div className="cart-amount">{formatPrice(cart?.subtotal ?? 0)}</div>
    </li>
  );
}
