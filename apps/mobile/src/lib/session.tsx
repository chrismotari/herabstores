import type { User } from '@herabs/shared';
import { useQuery, useQueryClient } from '@tanstack/react-query';
import * as Device from 'expo-device';
import { createContext, use, useCallback, useMemo, type ReactNode } from 'react';
import { Platform } from 'react-native';

import { ApiError, api, AUTH_TOKEN_KEY, CART_TOKEN_KEY, errorMessage, unwrap } from './api';
import { storage } from './storage';

export type FieldErrors = Record<string, string[]>;
export type AuthResult = { ok: true } | { ok: false; message: string; errors: FieldErrors };

export type RegisterInput = {
  fname: string;
  lname: string;
  phone: string;
  email: string;
  password: string;
  password_confirmation: string;
};

type SessionValue = {
  user: User | null;
  loading: boolean;
  login: (email: string, password: string) => Promise<AuthResult>;
  register: (input: RegisterInput) => Promise<AuthResult>;
  logout: () => Promise<void>;
};

const SessionContext = createContext<SessionValue | null>(null);

export function useSession(): SessionValue {
  const value = use(SessionContext);
  if (!value) throw new Error('useSession must be used inside <SessionProvider>');
  return value;
}

/** Shown in the customer's list of signed-in devices (Sanctum token name). */
const deviceName = () => Device.deviceName ?? Device.modelName ?? `Herab app (${Platform.OS})`;

const fail = (error: unknown): AuthResult => ({
  ok: false,
  message: errorMessage(error),
  errors: (error as { errors?: FieldErrors } | undefined)?.errors ?? {},
});

export const ME_KEY = ['me'] as const;

/**
 * Signed-in customer, via a Sanctum bearer token kept in the secure store.
 * Login/register send the guest cart token so the API merges the guest cart.
 */
export function SessionProvider({ children }: { children: ReactNode }) {
  const queryClient = useQueryClient();

  const me = useQuery({
    queryKey: ME_KEY,
    queryFn: async (): Promise<User | null> => {
      if (!(await storage.get(AUTH_TOKEN_KEY))) return null;
      try {
        return unwrap(await api.GET('/me')).data;
      } catch (e) {
        if (e instanceof ApiError && e.status === 401) {
          await storage.set(AUTH_TOKEN_KEY, null); // revoked or expired
          return null;
        }
        throw e;
      }
    },
    staleTime: Infinity,
  });

  const signedIn = useCallback(
    async (user: User, token: string | undefined) => {
      await storage.set(AUTH_TOKEN_KEY, token ?? null);
      await storage.set(CART_TOKEN_KEY, null); // the guest cart was merged into the account
      queryClient.setQueryData(ME_KEY, user);
      await queryClient.invalidateQueries({ queryKey: ['cart'] });
    },
    [queryClient],
  );

  const value = useMemo<SessionValue>(
    () => ({
      user: me.data ?? null,
      loading: me.isPending,
      login: async (email, password) => {
        const { data, error } = await api.POST('/auth/login', {
          body: { email, password, device_name: deviceName() },
        });
        if (!data) return fail(error);
        await signedIn(data.data, data.token);
        return { ok: true };
      },
      register: async (input) => {
        const { data, error } = await api.POST('/auth/register', {
          body: { ...input, device_name: deviceName() },
        });
        if (!data) return fail(error);
        await signedIn(data.data, data.token);
        return { ok: true };
      },
      logout: async () => {
        await api.POST('/auth/logout').catch(() => undefined); // sign out locally even if offline
        await storage.set(AUTH_TOKEN_KEY, null);
        queryClient.setQueryData(ME_KEY, null);
        queryClient.removeQueries({ queryKey: ['account'] });
        await queryClient.invalidateQueries({ queryKey: ['cart'] });
      },
    }),
    [me.data, me.isPending, signedIn, queryClient],
  );

  return <SessionContext value={value}>{children}</SessionContext>;
}
