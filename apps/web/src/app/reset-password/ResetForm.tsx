"use client";

import Link from "next/link";
import { useSearchParams } from "next/navigation";
import { useState, type FormEvent } from "react";
import { Field } from "@/components/auth/AuthCard";
import { browserApi, errorMessage } from "@/lib/browser-api";

/** Opened from the reset email: /reset-password?token=...&email=... */
export function ResetForm() {
  const params = useSearchParams();
  const token = params.get("token") ?? "";
  const email = params.get("email") ?? "";
  const [state, setState] = useState<{ done?: boolean; error?: string; busy?: boolean }>({});

  if (!token) return <p>This link is incomplete. <Link href="/forgot-password">Request a new one</Link>.</p>;

  async function onSubmit(event: FormEvent<HTMLFormElement>) {
    event.preventDefault();
    const form = new FormData(event.currentTarget);
    setState({ busy: true });
    const { data, error } = await browserApi.POST("/auth/reset-password", {
      body: {
        token,
        email: String(form.get("email")),
        password: String(form.get("password")),
        password_confirmation: String(form.get("password_confirmation")),
      },
    });
    setState(data ? { done: true } : { error: errorMessage(error) });
  }

  if (state.done) return <p role="status">Your password has been changed. <Link href="/login">Log in</Link>.</p>;

  return (
    <form onSubmit={onSubmit}>
      <Field name="email" label="Email" type="email" required defaultValue={email} autoComplete="email" />
      <Field name="password" label="New password" type="password" required minLength={8} autoComplete="new-password" />
      <Field name="password_confirmation" label="Confirm new password" type="password" required autoComplete="new-password" />
      {state.error && <p className="text-danger" role="alert">{state.error}</p>}
      <button type="submit" className="btn" disabled={state.busy}>Save new password</button>
    </form>
  );
}
