"use client";

import type { User } from "@herabs/shared";
import { createContext, useCallback, useContext, useEffect, useMemo, useState, type ReactNode } from "react";
import { useCart } from "@/components/cart/CartProvider";
import { browserApi, cartToken, errorMessage } from "@/lib/browser-api";

type Errors = Record<string, string[]>;
type Result = { ok: true } | { ok: false; message: string; errors: Errors };

type SessionValue = {
  user: User | null;
  loading: boolean;
  login: (email: string, password: string) => Promise<Result>;
  register: (input: { fname: string; lname: string; phone: string; email: string; password: string; password_confirmation: string }) => Promise<Result>;
  logout: () => Promise<void>;
  setUser: (user: User) => void;
};

const SessionContext = createContext<SessionValue | null>(null);

export function useSession() {
  const value = useContext(SessionContext);
  if (!value) throw new Error("useSession must be used inside <SessionProvider>");
  return value;
}

const fail = (error: unknown): Result => ({
  ok: false,
  message: errorMessage(error),
  errors: ((error as { errors?: Errors } | undefined)?.errors ?? {}),
});

/**
 * Web session via Sanctum SPA cookies (no tokens in the browser).
 * Login/register send the guest X-Cart-Token so the API merges the guest cart.
 */
export function SessionProvider({ children }: { children: ReactNode }) {
  const { refresh: refreshCart } = useCart();
  const [user, setUser] = useState<User | null>(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    void browserApi.GET("/me").then(({ data }) => {
      setUser(data?.data ?? null);
      setLoading(false);
    });
  }, []);

  const afterAuth = useCallback(
    async (next: User) => {
      setUser(next);
      cartToken.set(null); // the guest cart was merged into the account
      await refreshCart();
    },
    [refreshCart],
  );

  const value = useMemo<SessionValue>(
    () => ({
      user,
      loading,
      setUser,
      login: async (email, password) => {
        const { data, error } = await browserApi.POST("/auth/login", { body: { email, password } });
        if (!data) return fail(error);
        await afterAuth(data.data as unknown as User);
        return { ok: true };
      },
      register: async (input) => {
        const { data, error } = await browserApi.POST("/auth/register", { body: input });
        if (!data) return fail(error);
        await afterAuth(data.data as unknown as User);
        return { ok: true };
      },
      logout: async () => {
        await browserApi.POST("/auth/logout");
        setUser(null);
        await refreshCart();
      },
    }),
    [user, loading, afterAuth, refreshCart],
  );

  return <SessionContext.Provider value={value}>{children}</SessionContext.Provider>;
}
