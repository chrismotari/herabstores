"use client";

import { formatPrice } from "@herabs/shared";
import Link from "next/link";
import { CartLineImage, lineHref, lineName } from "@/components/cart/CartLineImage";
import { useSession } from "@/components/auth/SessionProvider";
import { useCart } from "@/components/cart/CartProvider";

/** Account icon; when logged in, the theme dropdown with account links. */
export function HeaderAccount() {
  const { user, logout } = useSession();

  return (
    <li className="header-cart-action">
      <div className="header-cart-wrap">
        <Link href={user ? "/account" : "/login"} aria-label={user ? "My account" : "Log in"}><i className="flaticon-user" /></Link>
        <ul className="minicart">
          {user ? (
            <>
              <li><div className="cart-content"><h4><Link href="/account">Hello, {user.fname}</Link></h4></div></li>
              <li>
                <div className="checkout-link">
                  <Link href="/account/orders">My orders</Link>
                  <button type="button" className="black-color border-0" onClick={() => logout()}>Log out</button>
                </div>
              </li>
            </>
          ) : (
            <li>
              <div className="checkout-link">
                <Link href="/login">Log in</Link>
                <Link className="black-color" href="/register">Register</Link>
              </div>
            </li>
          )}
        </ul>
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
                  <span className="f-right">{formatPrice(cart?.total ?? 0)}</span>
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
      <div className="cart-amount">{formatPrice(cart?.total ?? 0)}</div>
    </li>
  );
}
