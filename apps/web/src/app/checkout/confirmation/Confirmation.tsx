"use client";

import { formatPrice, type Order } from "@herabs/shared";
import Link from "next/link";
import { useSearchParams } from "next/navigation";
import { useSyncExternalStore } from "react";
import { LAST_ORDER_KEY } from "../CheckoutForm";

const readLastOrder = (): string | null => {
  try {
    return sessionStorage.getItem(LAST_ORDER_KEY);
  } catch {
    return null;
  }
};

export function Confirmation() {
  const number = useSearchParams().get("order");
  const raw = useSyncExternalStore(() => () => {}, readLastOrder, () => null);
  const parsed = raw ? (JSON.parse(raw) as Order) : null;
  const order = parsed && parsed.order_number === number ? parsed : null;

  return (
    <div className="row justify-content-center">
      <div className="col-lg-8 text-center">
        <h1 className="h2">Thank you! Your order is placed.</h1>
        {number && <p className="lead">Order number <strong>{number}</strong></p>}
        <p>We&apos;ll call you to confirm delivery. Please have the total ready: you pay on delivery.</p>

        {order && (
          <div className="shop-cart-total text-left mt-40">
            <ul>
              {order.items.map((item) => (
                <li key={`${item.type}-${item.product_id ?? item.basket_id}`} className="d-flex justify-content-between">
                  <span>{item.quantity} x {item.name}</span>
                  <span>{formatPrice(item.line_total)}</span>
                </li>
              ))}
              <li className="sub-total"><span>Subtotal</span> {formatPrice(order.subtotal)}</li>
              {order.discount > 0 && <li><span>Discount ({order.discount_code})</span> -{formatPrice(order.discount)}</li>}
              <li><span>Delivery</span> {formatPrice(order.delivery_fee)}</li>
              <li className="cart-total-amount"><span>Total due on delivery</span> <span className="amount">{formatPrice(order.total)}</span></li>
            </ul>
          </div>
        )}

        <div className="mt-40">
          <Link href="/shop" className="btn rounded-btn mr-2">Continue shopping</Link>
          <Link href="/account/orders" className="btn rounded-btn">My orders</Link>
        </div>
      </div>
    </div>
  );
}
