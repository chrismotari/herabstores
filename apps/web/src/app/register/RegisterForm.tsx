"use client";

import Link from "next/link";
import { useRouter, useSearchParams } from "next/navigation";
import { useState, type FormEvent } from "react";
import { Field } from "@/components/auth/AuthCard";
import { useSession } from "@/components/auth/SessionProvider";
import { safeNext } from "../login/LoginForm";

export function RegisterForm() {
  const { register } = useSession();
  const router = useRouter();
  const next = safeNext(useSearchParams().get("next"));
  const [errors, setErrors] = useState<Record<string, string[]>>({});
  const [busy, setBusy] = useState(false);

  async function onSubmit(event: FormEvent<HTMLFormElement>) {
    event.preventDefault();
    const form = new FormData(event.currentTarget);
    const get = (k: string) => String(form.get(k) ?? "");
    setBusy(true);
    const result = await register({
      fname: get("fname"), lname: get("lname"), phone: get("phone"), email: get("email"),
      password: get("password"), password_confirmation: get("password_confirmation"),
    });
    setBusy(false);
    if (result.ok) router.push(next);
    else setErrors(Object.keys(result.errors).length ? result.errors : { form: [result.message] });
  }

  const e = (k: string) => errors[k]?.[0];

  return (
    <form onSubmit={onSubmit}>
      <div className="row">
        <div className="col-sm-6"><Field name="fname" label="First name" required autoComplete="given-name" error={e("fname")} /></div>
        <div className="col-sm-6"><Field name="lname" label="Last name" required autoComplete="family-name" error={e("lname")} /></div>
      </div>
      <Field name="phone" label="Phone" type="tel" required autoComplete="tel" error={e("phone")} />
      <Field name="email" label="Email" type="email" required autoComplete="email" error={e("email")} />
      <Field name="password" label="Password (at least 8 characters)" type="password" required minLength={8} autoComplete="new-password" error={e("password")} />
      <Field name="password_confirmation" label="Confirm password" type="password" required autoComplete="new-password" />
      <p className="small">Your delivery address is asked for at checkout.</p>
      {e("form") && <p className="text-danger" role="alert">{e("form")}</p>}
      <button type="submit" className="btn" disabled={busy}>{busy ? "Creating..." : "Create account"}</button>
      <p className="mt-20 mb-0">Already have an account? <Link href={`/login?next=${encodeURIComponent(next)}`}>Log in</Link></p>
    </form>
  );
}
