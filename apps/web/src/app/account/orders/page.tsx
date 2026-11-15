"use client";

import { formatPrice, type Order } from "@herabs/shared";
import Link from "next/link";
import { useEffect, useState } from "react";
import { browserApi } from "@/lib/browser-api";

export const STATUS_LABEL: Record<string, string> = {
  new: "Received",
  dispatched: "On the way",
  delivered: "Delivered",
  canceled: "Canceled",
};

export default function OrdersPage() {
  const [orders, setOrders] = useState<Order[] | null>(null);

  useEffect(() => {
    void browserApi.GET("/me/orders").then(({ data }) => setOrders(data?.data ?? []));
  }, []);

  if (!orders) return <p>Loading your orders...</p>;

  return (
    <div>
      <h1 className="h3 mb-30">My orders</h1>
      {orders.length === 0 ? (
        <p>No orders yet. <Link href="/shop">Start shopping</Link>.</p>
      ) : (
        <div className="cart-wrapper account-orders">
          <div className="table-responsive">
            <table className="table mb-0">
              <thead>
                <tr><th>Order</th><th>Placed</th><th>Status</th><th>Total</th><th /></tr>
              </thead>
              <tbody>
                {orders.map((order) => (
                  <tr key={order.id}>
                    <td>{order.order_number}</td>
                    <td>{order.created_at ? new Date(order.created_at).toLocaleDateString("en-KE") : ""}</td>
                    <td>{STATUS_LABEL[order.status ?? ""] ?? order.status}</td>
                    <td>{formatPrice(order.total)}</td>
                    <td><Link href={`/account/orders/${order.id}`}>View</Link></td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        </div>
      )}
    </div>
  );
}
