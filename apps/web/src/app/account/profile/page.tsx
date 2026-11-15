"use client";

import { useRouter } from "next/navigation";
import { useState, type FormEvent } from "react";
import { Field } from "@/components/auth/AuthCard";
import { useSession } from "@/components/auth/SessionProvider";
import { browserApi, errorMessage } from "@/lib/browser-api";

type Errors = Record<string, string[]>;

export default function ProfilePage() {
  const { user, setUser, logout } = useSession();
  const router = useRouter();
  const [profile, setProfile] = useState<{ errors?: Errors; saved?: boolean; busy?: boolean }>({});
  const [password, setPassword] = useState<{ errors?: Errors; saved?: boolean; busy?: boolean }>({});
  const [deleting, setDeleting] = useState<{ error?: string; busy?: boolean }>({});

  if (!user) return null;
  const text = (form: FormData, k: string) => String(form.get(k) ?? "").trim();

  async function saveProfile(event: FormEvent<HTMLFormElement>) {
    event.preventDefault();
    const form = new FormData(event.currentTarget);
    setProfile({ busy: true });
    const { data, error } = await browserApi.PATCH("/me", {
      body: {
        fname: text(form, "fname"), lname: text(form, "lname"), phone: text(form, "phone"), email: text(form, "email"),
        address: text(form, "address") || null, city: text(form, "city") || null,
      },
    });
    if (data) {
      setUser(data.data);
      setProfile({ saved: true });
    } else {
      setProfile({ errors: (error as { errors?: Errors })?.errors ?? { form: [errorMessage(error)] } });
    }
  }

  async function savePassword(event: FormEvent<HTMLFormElement>) {
    event.preventDefault();
    const formEl = event.currentTarget;
    const form = new FormData(formEl);
    setPassword({ busy: true });
    const { data, error } = await browserApi.PUT("/me/password", {
      body: { current_password: text(form, "current_password"), password: text(form, "password"), password_confirmation: text(form, "password_confirmation") } as never,
    });
    if (data) {
      formEl.reset();
      setPassword({ saved: true });
    } else {
      setPassword({ errors: (error as { errors?: Errors })?.errors ?? { form: [errorMessage(error)] } });
    }
  }

  async function deleteAccount(event: FormEvent<HTMLFormElement>) {
    event.preventDefault();
    if (!window.confirm("Delete your account? Your order history will no longer be linked to you. This can't be undone.")) return;
    const form = new FormData(event.currentTarget);
    setDeleting({ busy: true });
    // Password goes in the JSON body (never the URL). The generated types list it as a
    // query param because Scramble guesses that for DELETE routes.
    const { data, error } = await browserApi.DELETE("/me", { body: { password: text(form, "password") } } as never);
    if (data) {
      await logout();
      router.push("/");
    } else {
      setDeleting({ error: errorMessage(error) });
    }
  }

  const pe = (k: string) => profile.errors?.[k]?.[0];
  const we = (k: string) => password.errors?.[k]?.[0];

  return (
    <div>
      <h1 className="h3 mb-30">Profile &amp; password</h1>

      <form onSubmit={saveProfile} className="mb-50">
        <h2 className="h5">Your details</h2>
        <div className="row">
          <div className="col-sm-6"><Field name="fname" label="First name" required defaultValue={user.fname} error={pe("fname")} /></div>
          <div className="col-sm-6"><Field name="lname" label="Last name" required defaultValue={user.lname} error={pe("lname")} /></div>
          <div className="col-sm-6"><Field name="phone" label="Phone" type="tel" required defaultValue={user.phone} error={pe("phone")} /></div>
          <div className="col-sm-6"><Field name="email" label="Email" type="email" required defaultValue={user.email} error={pe("email")} /></div>
          <div className="col-sm-8"><Field name="address" label="Default delivery address" defaultValue={user.address ?? ""} error={pe("address")} /></div>
          <div className="col-sm-4"><Field name="city" label="Town / city" defaultValue={user.city ?? ""} error={pe("city")} /></div>
        </div>
        {pe("form") && <p className="text-danger">{pe("form")}</p>}
        {profile.saved && <p className="text-success" role="status">Saved.</p>}
        <button type="submit" className="btn" disabled={profile.busy}>Save details</button>
      </form>

      <form onSubmit={savePassword} className="mb-50">
        <h2 className="h5">Change password</h2>
        <Field name="current_password" label="Current password" type="password" required autoComplete="current-password" error={we("current_password")} />
        <Field name="password" label="New password" type="password" required minLength={8} autoComplete="new-password" error={we("password")} />
        <Field name="password_confirmation" label="Confirm new password" type="password" required autoComplete="new-password" />
        {password.saved && <p className="text-success" role="status">Password changed.</p>}
        <button type="submit" className="btn" disabled={password.busy}>Change password</button>
      </form>

      <form onSubmit={deleteAccount}>
        <h2 className="h5 text-danger">Delete account</h2>
        <p>Your past orders are kept for our records but are no longer linked to you.</p>
        <Field name="password" label="Confirm with your password" type="password" required autoComplete="current-password" error={deleting.error} />
        <button type="submit" className="btn btn-danger" disabled={deleting.busy}>Delete my account</button>
      </form>
    </div>
  );
}
