"use client";

import { formatPrice, type Order } from "@herabs/shared";
import Link from "next/link";
import { useParams } from "next/navigation";
import { useEffect, useState } from "react";
import { browserApi } from "@/lib/browser-api";
import { STATUS_LABEL } from "../page";

const STEPS = ["new", "dispatched", "delivered"] as const;

export default function OrderPage() {
  const { id } = useParams<{ id: string }>();
  const [order, setOrder] = useState<Order | null | undefined>(undefined);

  useEffect(() => {
    void browserApi.GET("/me/orders/{order}", { params: { path: { order: id } } }).then(({ data }) => setOrder(data?.data ?? null));
  }, [id]);

  if (order === undefined) return <p>Loading order...</p>;
  if (order === null) return <p>Order not found. <Link href="/account/orders">Back to my orders</Link></p>;

  const step = STEPS.indexOf((order.status ?? "new") as (typeof STEPS)[number]);
  const when = (iso: string | null) => (iso ? new Date(iso).toLocaleString("en-KE") : null);

  return (
    <div>
      <h1 className="h3">Order {order.order_number}</h1>
      {order.status === "canceled" ? (
        <p className="text-danger">This order was canceled.</p>
      ) : (
        <ol className="list-inline mb-30" aria-label="Order progress">
          {STEPS.map((s, i) => (
            <li key={s} className={`list-inline-item mr-4${i <= step ? " font-weight-bold" : " text-muted"}`}>
              {i <= step ? "✓ " : ""}{STATUS_LABEL[s]}
              {s === "dispatched" && order.shipped_at && <small className="d-block">{when(order.shipped_at)}</small>}
              {s === "delivered" && order.delivered_at && <small className="d-block">{when(order.delivered_at)}</small>}
            </li>
          ))}
        </ol>
      )}

      <div className="shop-cart-total order-detail-summary">
        <ul className="summary-list">
          {order.items.map((item) => (
            <li key={`${item.type}-${item.product_id ?? item.basket_id}`} className="summary-item">
              <span>{item.quantity} x {item.name}</span><span>{formatPrice(item.line_total)}</span>
            </li>
          ))}
          <li className="sub-total"><span>Subtotal</span><span>{formatPrice(order.subtotal)}</span></li>
          {order.discount > 0 && <li><span>Discount ({order.discount_code})</span><span>-{formatPrice(order.discount)}</span></li>}
          <li><span>Delivery</span><span>{formatPrice(order.delivery_fee)}</span></li>
          <li className="cart-total-amount"><span>Total ({order.payment_status === "paid" ? "paid" : "pay on delivery"})</span> <span>{formatPrice(order.total)}</span></li>
        </ul>
      </div>

      <h2 className="h5 mt-30">Delivery</h2>
      <p>
        {order.delivery.fname} {order.delivery.lname}, {order.delivery.phone}<br />
        {order.delivery.address}, {order.delivery.city}
        {order.delivery.zone && <> ({order.delivery.zone.name})</>}
      </p>
      <Link href="/account/orders">Back to my orders</Link>
    </div>
  );
}
