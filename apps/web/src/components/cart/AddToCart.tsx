"use client";

import { useState } from "react";
import { useCart } from "./CartProvider";

type Props = {
  productId?: string;
  basketId?: string;
  maxQuantity: number;
  /** "card" = product grid, "detail" = product page */
  variant?: "card" | "detail";
};

export function AddToCart({ productId, basketId, maxQuantity, variant = "card" }: Props) {
  const { add } = useCart();
  const [quantity, setQuantity] = useState(1);
  const [state, setState] = useState<{ status: "idle" | "adding" | "added" | "error"; message?: string }>({ status: "idle" });
  const soldOut = maxQuantity < 1;

  const set = (next: number) => setQuantity(Math.max(1, Math.min(next, Math.max(1, maxQuantity))));

  async function onAdd() {
    setState({ status: "adding" });
    const result = await add({ productId, basketId, quantity });
    setState(result.ok ? { status: "added" } : { status: "error", message: result.message });
  }

  const stepper = (
    <div className="cart-plus-minus">
      <input
        className="cart-plus-minus-box"
        inputMode="numeric"
        aria-label="Quantity"
        value={quantity}
        onChange={(e) => set(Number(e.target.value) || 1)}
        disabled={soldOut}
      />
      <button type="button" className="dec qtybutton" aria-label="Decrease" onClick={() => set(quantity - 1)} disabled={soldOut}>-</button>
      <button type="button" className="inc qtybutton" aria-label="Increase" onClick={() => set(quantity + 1)} disabled={soldOut}>+</button>
    </div>
  );

  const button = (
    <button
      type="button"
      className={variant === "detail" ? "btn" : "bg-orange rounded btn-secondary"}
      onClick={onAdd}
      disabled={soldOut || state.status === "adding"}
    >
      {soldOut ? "Out of stock" : state.status === "adding" ? "Adding..." : state.status === "added" ? "Added ✓" : "Add to cart"}
    </button>
  );

  const feedback = state.status === "error" && <p className="text-danger small mt-2" role="alert">{state.message}</p>;

  if (variant === "card") {
    // Grid cards: one button (adds 1). Quantity is chosen on the product page or in the cart.
    return (
      <div className="add-to-cart mt-2">
        {button}
        {feedback}
      </div>
    );
  }

  if (variant === "detail") {
    return (
      <div className="shop-perched-info">
        <div className="sd-cart-wrap">{stepper}</div>
        {button}
        {feedback}
      </div>
    );
  }

  return (
    <div className="sp-cart-wrap">
      {stepper}
      <div className="add-to-cart mt-3">{button}</div>
      {feedback}
    </div>
  );
}
