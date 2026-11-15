"use client";

import { createApiClient } from "@herabs/shared";
import { useState, type FormEvent } from "react";
import { PUBLIC_API_URL } from "@/lib/config";

const api = createApiClient({ baseUrl: PUBLIC_API_URL });

export function NewsletterForm() {
  const [status, setStatus] = useState<"idle" | "sending" | "done" | "error">("idle");

  async function onSubmit(event: FormEvent<HTMLFormElement>) {
    event.preventDefault();
    const email = new FormData(event.currentTarget).get("email") as string;
    setStatus("sending");
    const { error } = await api.POST("/contacts", { body: { email, consented: true, subject: "Newsletter" } });
    setStatus(error ? "error" : "done");
  }

  if (status === "done") return <p>Thanks, you&apos;re subscribed.</p>;

  return (
    <form onSubmit={onSubmit}>
      <input type="email" placeholder="Email Address" name="email" required aria-label="Email address" />
      <button type="submit" aria-label="Subscribe" disabled={status === "sending"}><i className="flaticon-send" /></button>
      {status === "error" && <p role="alert">Sorry, that didn&apos;t work. Please try again.</p>}
    </form>
  );
}
