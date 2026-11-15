"use client";

import { useState, type FormEvent } from "react";
import { Field } from "@/components/auth/AuthCard";
import { browserApi, errorMessage } from "@/lib/browser-api";

export function ForgotForm() {
  const [state, setState] = useState<{ sent?: string; error?: string; busy?: boolean }>({});

  async function onSubmit(event: FormEvent<HTMLFormElement>) {
    event.preventDefault();
    const email = String(new FormData(event.currentTarget).get("email"));
    setState({ busy: true });
    const { data, error } = await browserApi.POST("/auth/forgot-password", { body: { email } });
    setState(data ? { sent: (data as { message: string }).message } : { error: errorMessage(error) });
  }

  if (state.sent) return <p role="status">{state.sent} Check your inbox for the link.</p>;

  return (
    <form onSubmit={onSubmit}>
      <p>Enter your email and we&apos;ll send you a link to choose a new password.</p>
      <Field name="email" label="Email" type="email" required autoComplete="email" error={state.error} />
      <button type="submit" className="btn" disabled={state.busy}>Send reset link</button>
    </form>
  );
}
