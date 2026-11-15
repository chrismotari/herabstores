"use client";

import { formatPrice, type CartItem } from "@herabs/shared";
import Link from "next/link";
import { useState } from "react";
import { useCart } from "@/components/cart/CartProvider";
import { CartLineImage, lineHref, lineMax, lineName } from "@/components/cart/CartLineImage";

export function CartView() {
  const { cart, loading } = useCart();
  const items = cart?.items ?? [];

  if (loading) return <p>Loading your cart...</p>;
  if (items.length === 0) {
    return (
      <div className="text-center">
        <h1 className="h3">Your cart is empty</h1>
        <Link href="/shop" className="btn rounded-btn mt-20">Start shopping</Link>
      </div>
    );
  }

  return (
    <div className="row justify-content-center">
      <div className="col-xl-7 col-lg-12">
        <h1 className="h3 mb-30">Your cart</h1>
        <div className="cart-wrapper">
          <div className="table-responsive">
            <table className="table mb-0">
              <thead>
                <tr>
                  <th className="product-thumbnail" />
                  <th className="product-name">Product</th>
                  <th className="product-price">Price</th>
                  <th className="product-quantity">Quantity</th>
                  <th className="product-subtotal">Subtotal</th>
                  <th className="product-delete" />
                </tr>
              </thead>
              <tbody>
                {items.map((item) => <CartRow key={item.id} item={item} />)}
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div className="col-xl-5 col-lg-12">
        <div className="shop-cart-total">
          <h2 className="title h3">Cart totals</h2>
          <div className="shop-cart-widget">
            <ul>
              <li className="sub-total"><span>Subtotal</span> {formatPrice(cart?.subtotal ?? 0)}</li>
              <li><span>Delivery</span> Calculated at checkout</li>
              <li className="cart-total-amount"><span>Payment</span> <span className="amount">Pay on delivery</span></li>
            </ul>
            <Link href="/checkout" className="btn">Proceed to checkout</Link>
          </div>
        </div>
      </div>
    </div>
  );
}

function CartRow({ item }: { item: CartItem }) {
  const { update, remove } = useCart();
  const [error, setError] = useState<string | null>(null);
  const max = lineMax(item);

  async function change(quantity: number) {
    setError(null);
    const result = await update(item.id, quantity);
    if (!result.ok) setError(result.message);
  }

  return (
    <tr>
      <td className="product-thumbnail"><Link href={lineHref(item)}><CartLineImage item={item} /></Link></td>
      <td className="product-name">
        <h4><Link href={lineHref(item)}>{lineName(item)}</Link></h4>
        {item.type === "basket" && <small>Basket</small>}
        {error && <small className="d-block text-danger" role="alert">{error}</small>}
      </td>
      <td className="product-price">{formatPrice(item.unit_price)}</td>
      <td className="product-quantity">
        <div className="cart-plus-minus">
          <input className="cart-plus-minus-box" readOnly value={item.quantity} aria-label="Quantity" />
          <button type="button" className="dec qtybutton" aria-label="Decrease" onClick={() => change(item.quantity - 1)}>-</button>
          <button type="button" className="inc qtybutton" aria-label="Increase" disabled={item.quantity >= max} onClick={() => change(item.quantity + 1)}>+</button>
        </div>
      </td>
      <td className="product-subtotal"><span>{formatPrice(item.line_total)}</span></td>
      <td className="product-delete">
        <button type="button" className="btn-link border-0 bg-transparent" aria-label={`Remove ${lineName(item)}`} onClick={() => remove(item.id)}>
          <i className="far fa-trash-alt" />
        </button>
      </td>
    </tr>
  );
}
