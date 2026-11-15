"use client";

import { formatPrice } from "@herabs/shared";
import { useState, type FormEvent } from "react";
import { useCart } from "./CartProvider";

/** Enter or remove a discount code (one per order). */
export function DiscountForm() {
  const { cart, applyDiscount, removeDiscount } = useCart();
  const [error, setError] = useState<string | null>(null);
  const [busy, setBusy] = useState(false);
  const discount = cart?.discount;

  async function onSubmit(event: FormEvent<HTMLFormElement>) {
    event.preventDefault();
    const code = String(new FormData(event.currentTarget).get("code") ?? "").trim();
    if (!code) return;
    setBusy(true);
    const result = await applyDiscount(code);
    setBusy(false);
    setError(result.ok ? null : result.message);
  }

  if (discount) {
    return (
      <div className="mb-20">
        <p className="mb-1">
          Code <strong>{discount.code}</strong>
          {discount.name && <> ({discount.name})</>}: {discount.error ? <span className="text-danger">{discount.error}</span> : <>-{formatPrice(discount.amount)}</>}
        </p>
        <button type="button" className="btn-link border-0 bg-transparent p-0" onClick={() => removeDiscount()}>Remove code</button>
      </div>
    );
  }

  return (
    <form onSubmit={onSubmit} className="mb-20">
      <label className="form-label" htmlFor="discount-code">Discount code</label>
      <div className="d-flex">
        <input id="discount-code" name="code" className={`form-control mr-2${error ? " is-invalid" : ""}`} autoComplete="off" placeholder="Enter code" />
        <button type="submit" className="card-cart-btn" disabled={busy}>{busy ? "..." : "Apply"}</button>
      </div>
      {error && <div className="text-danger small mt-1" role="alert">{error}</div>}
    </form>
  );
}
