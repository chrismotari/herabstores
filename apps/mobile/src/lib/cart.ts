import type { Cart } from '@herabs/shared';
import { queryOptions, useQuery, useQueryClient } from '@tanstack/react-query';
import { useMemo } from 'react';

import { api, CART_TOKEN_KEY, errorMessage, unwrap } from './api';
import { storage } from './storage';

/** The order just placed, kept for the confirmation screen (guests can't fetch it again). */
export const placedOrderKey = (number: string) => ['orders', 'placed', number] as const;

export type CartResult = { ok: true } | { ok: false; message: string };

/** Guests keep the token the API hands back; signed-in carts have none. */
async function accept(cart: Cart): Promise<Cart> {
  if (cart.token) await storage.set(CART_TOKEN_KEY, cart.token);
  return cart;
}

export const cartQuery = queryOptions({
  queryKey: ['cart'],
  queryFn: async () => accept(unwrap(await api.GET('/cart')).data),
  staleTime: 0,
});

export function useCart() {
  const cart = useQuery(cartQuery);
  return { ...cart, count: cart.data?.items_count ?? 0 };
}

export function useCartActions() {
  const queryClient = useQueryClient();

  return useMemo(() => {
    const run = async (call: Promise<{ data?: { data: Cart }; error?: unknown }>): Promise<CartResult> => {
      const { data, error } = await call;
      if (!data) return { ok: false, message: errorMessage(error) };
      queryClient.setQueryData(cartQuery.queryKey, await accept(data.data));
      return { ok: true };
    };

    return {
      add: (item: { productId?: string; basketId?: string; quantity: number }) =>
        run(
          api.POST('/cart/items', {
            body: { product_id: item.productId, basket_id: item.basketId, quantity: item.quantity },
          }),
        ),
      update: (itemId: string, quantity: number) =>
        run(api.PATCH('/cart/items/{item}', { params: { path: { item: itemId } }, body: { quantity } })),
      remove: (itemId: string) => run(api.DELETE('/cart/items/{item}', { params: { path: { item: itemId } } })),
      applyDiscount: (code: string) => run(api.POST('/cart/discount', { body: { code } })),
      removeDiscount: () => run(api.DELETE('/cart/discount')),
    };
  }, [queryClient]);
}
