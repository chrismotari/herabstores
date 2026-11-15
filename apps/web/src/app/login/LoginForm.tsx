"use client";

import Link from "next/link";
import { useRouter, useSearchParams } from "next/navigation";
import { useState, type FormEvent } from "react";
import { Field } from "@/components/auth/AuthCard";
import { useSession } from "@/components/auth/SessionProvider";

/** Only allow redirects within this site. */
export function safeNext(next: string | null, fallback = "/account") {
  return next && next.startsWith("/") && !next.startsWith("//") ? next : fallback;
}

export function LoginForm() {
  const { login } = useSession();
  const router = useRouter();
  const next = safeNext(useSearchParams().get("next"));
  const [error, setError] = useState<string | null>(null);
  const [busy, setBusy] = useState(false);

  async function onSubmit(event: FormEvent<HTMLFormElement>) {
    event.preventDefault();
    const form = new FormData(event.currentTarget);
    setBusy(true);
    const result = await login(String(form.get("email")), String(form.get("password")));
    setBusy(false);
    if (result.ok) router.push(next);
    else setError(result.message);
  }

  return (
    <form onSubmit={onSubmit}>
      <Field name="email" label="Email" type="email" required autoComplete="email" />
      <Field name="password" label="Password" type="password" required autoComplete="current-password" />
      {error && <p className="text-danger" role="alert">{error}</p>}
      <button type="submit" className="btn" disabled={busy}>{busy ? "Logging in..." : "Log in"}</button>
      <p className="mt-20 mb-0">
        <Link href="/forgot-password">Forgot your password?</Link>
        <br />
        New here? <Link href={`/register?next=${encodeURIComponent(next)}`}>Create an account</Link>
      </p>
    </form>
  );
}
