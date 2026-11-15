import type { Order, Product } from '@herabs/shared';
import { infiniteQueryOptions, queryOptions, useQuery, useQueryClient } from '@tanstack/react-query';
import { useMemo } from 'react';

import { api, errorMessage, unwrap } from './api';
import { useSession } from './session';

/** Everything under ['account'] is dropped on sign out. */
export const STATUS_LABEL: Record<NonNullable<Order['status']>, string> = {
  new: 'Received',
  dispatched: 'On the way',
  delivered: 'Delivered',
  canceled: 'Canceled',
};

export const ordersQuery = infiniteQueryOptions({
  queryKey: ['account', 'orders'],
  initialPageParam: 1,
  queryFn: async ({ pageParam }) => unwrap(await api.GET('/me/orders', { params: { query: { page: pageParam } } })),
  getNextPageParam: ({ meta }) => (meta.current_page < meta.last_page ? meta.current_page + 1 : undefined),
});

export const orderQuery = (id: string) =>
  queryOptions({
    queryKey: ['account', 'orders', id],
    queryFn: async (): Promise<Order> =>
      unwrap(await api.GET('/me/orders/{order}', { params: { path: { order: id } } })).data,
    staleTime: 30_000, // status changes as the order moves
  });

export const favouritesQuery = queryOptions({
  queryKey: ['account', 'favourites'],
  queryFn: async (): Promise<Product[]> => unwrap(await api.GET('/me/favourites')).data,
});

/** Favourite ids (when signed in) and a toggle. */
export function useFavourites() {
  const { user } = useSession();
  const queryClient = useQueryClient();
  const favourites = useQuery({ ...favouritesQuery, enabled: !!user });

  return useMemo(() => {
    const ids = new Set((favourites.data ?? []).map((p) => p.id));
    return {
      ids,
      signedIn: !!user,
      toggle: async (productId: string): Promise<string | null> => {
        const { error } = ids.has(productId)
          ? await api.DELETE('/me/favourites/{product}', { params: { path: { product: productId } } })
          : await api.POST('/me/favourites', { body: { product_id: productId } });
        await queryClient.invalidateQueries({ queryKey: favouritesQuery.queryKey });
        return error ? errorMessage(error) : null;
      },
    };
  }, [favourites.data, user, queryClient]);
}
