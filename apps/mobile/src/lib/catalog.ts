import type { Basket, Category, DeliveryZone, Product } from '@herabs/shared';
import { infiniteQueryOptions, queryOptions } from '@tanstack/react-query';

import { api, unwrap } from './api';

export type ProductSort = 'newest' | 'price_asc' | 'price_desc' | 'name';
export type ProductFilters = { q?: string; category?: string; sort?: ProductSort };

const PER_PAGE = 20;

export const categoriesQuery = queryOptions({
  queryKey: ['categories'],
  queryFn: async (): Promise<Category[]> => unwrap(await api.GET('/categories')).data,
});

export const categoryQuery = (slug: string) =>
  queryOptions({
    queryKey: ['categories', slug],
    queryFn: async (): Promise<Category> =>
      unwrap(await api.GET('/categories/{slug}', { params: { path: { slug } } })).data,
  });

export const productsQuery = (filters: ProductFilters) =>
  infiniteQueryOptions({
    queryKey: ['products', 'list', filters],
    initialPageParam: 1,
    queryFn: async ({ pageParam }) =>
      unwrap(
        await api.GET('/products', {
          params: {
            query: {
              q: filters.q || undefined,
              category: filters.category,
              sort: filters.sort,
              page: pageParam,
              per_page: PER_PAGE,
            },
          },
        }),
      ),
    getNextPageParam: ({ meta }) => (meta.current_page < meta.last_page ? meta.current_page + 1 : undefined),
  });

export const productQuery = (slug: string) =>
  queryOptions({
    queryKey: ['products', 'detail', slug],
    queryFn: async (): Promise<Product> =>
      unwrap(await api.GET('/products/{slug}', { params: { path: { slug } } })).data,
  });

export const basketsQuery = queryOptions({
  queryKey: ['baskets'],
  queryFn: async (): Promise<Basket[]> => unwrap(await api.GET('/baskets')).data,
});

export const basketQuery = (slug: string) =>
  queryOptions({
    queryKey: ['baskets', slug],
    queryFn: async (): Promise<Basket> =>
      unwrap(await api.GET('/baskets/{slug}', { params: { path: { slug } } })).data,
  });

export const zonesQuery = queryOptions({
  queryKey: ['delivery-zones'],
  queryFn: async (): Promise<DeliveryZone[]> => unwrap(await api.GET('/delivery-zones')).data,
  staleTime: 60 * 60_000,
});
