"use client";

import type { Cart } from "@herabs/shared";
import { createContext, useCallback, useContext, useEffect, useMemo, useState, type ReactNode } from "react";
import { browserApi, cartToken, errorMessage } from "@/lib/browser-api";

type AddInput = { productId?: string; basketId?: string; quantity?: number };
type Result = { ok: true } | { ok: false; message: string };

type CartContextValue = {
  cart: Cart | null;
  loading: boolean;
  count: number;
  add: (input: AddInput) => Promise<Result>;
  update: (itemId: string, quantity: number) => Promise<Result>;
  remove: (itemId: string) => Promise<Result>;
  applyDiscount: (code: string) => Promise<Result>;
  removeDiscount: () => Promise<Result>;
  refresh: () => Promise<void>;
};

const CartContext = createContext<CartContextValue | null>(null);

export function useCart() {
  const value = useContext(CartContext);
  if (!value) throw new Error("useCart must be used inside <CartProvider>");
  return value;
}

/**
 * Holds the current cart (guest token or logged-in user's cart) for the header,
 * product pages, cart and checkout.
 */
export function CartProvider({ children }: { children: ReactNode }) {
  const [cart, setCart] = useState<Cart | null>(null);
  const [loading, setLoading] = useState(true);

  const accept = useCallback((next: Cart) => {
    setCart(next);
    if (next.token) cartToken.set(next.token); // guests keep their token
  }, []);

  const refresh = useCallback(async () => {
    const { data } = await browserApi.GET("/cart");
    if (data) accept(data.data);
    setLoading(false);
  }, [accept]);

  useEffect(() => {
    // Initial load once mounted (cart is per-browser, never part of the static page)
    void browserApi.GET("/cart").then(({ data }) => {
      if (data) accept(data.data);
      setLoading(false);
    });
  }, [accept]);

  const value = useMemo<CartContextValue>(() => {
    const run = async (call: Promise<{ data?: { data: Cart }; error?: unknown }>): Promise<Result> => {
      const { data, error } = await call;
      if (data) {
        accept(data.data);
        return { ok: true };
      }
      return { ok: false, message: errorMessage(error) };
    };

    return {
      cart,
      loading,
      count: cart?.items_count ?? 0,
      refresh,
      add: ({ productId, basketId, quantity = 1 }) =>
        run(browserApi.POST("/cart/items", { body: { product_id: productId, basket_id: basketId, quantity } as never })),
      update: (itemId, quantity) =>
        run(browserApi.PATCH("/cart/items/{item}", { params: { path: { item: itemId } }, body: { quantity } })),
      remove: (itemId) => run(browserApi.DELETE("/cart/items/{item}", { params: { path: { item: itemId } } })),
      applyDiscount: (code) => run(browserApi.POST("/cart/discount", { body: { code } })),
      removeDiscount: () => run(browserApi.DELETE("/cart/discount")),
    };
  }, [cart, loading, refresh, accept]);

  return <CartContext.Provider value={value}>{children}</CartContext.Provider>;
}
