import { QueryClient } from '@tanstack/react-query';

import { ApiError } from './api';

export const queryClient = new QueryClient({
  defaultOptions: {
    queries: {
      staleTime: 5 * 60_000,
      // Don't retry 4xx (not found, validation); retry network/5xx twice.
      retry: (count, error) => !(error instanceof ApiError && error.status < 500) && count < 2,
    },
  },
});
