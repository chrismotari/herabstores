"use client";

import { useState, type FormEvent } from "react";
import { browserApi, errorMessage } from "@/lib/browser-api";

export function ContactForm() {
  const [state, setState] = useState<{ status: "idle" | "sending" | "sent" | "error"; message?: string }>({ status: "idle" });

  async function onSubmit(event: FormEvent<HTMLFormElement>) {
    event.preventDefault();
    const form = new FormData(event.currentTarget);
    setState({ status: "sending" });

    const { error } = await browserApi.POST("/contacts", {
      body: {
        name: String(form.get("name")),
        email: String(form.get("email")),
        phone: String(form.get("phone") || "") || null,
        subject: String(form.get("subject")),
        message: String(form.get("message")),
        consented: form.get("consented") === "on",
      },
    });

    setState(error ? { status: "error", message: errorMessage(error) } : { status: "sent" });
  }

  if (state.status === "sent") {
    return <p className="alert alert-success" role="status">Thanks! We&apos;ll be in touch soon.</p>;
  }

  return (
    <form className="contact-form" onSubmit={onSubmit}>
      <div className="row">
        <div className="col-md-6 mb-20">
          <label className="d-block" htmlFor="c-name">Your name *</label>
          <input id="c-name" name="name" className="form-control" required autoComplete="name" />
        </div>
        <div className="col-md-6 mb-20">
          <label className="d-block" htmlFor="c-phone">Phone</label>
          <input id="c-phone" name="phone" type="tel" className="form-control" autoComplete="tel" />
        </div>
        <div className="col-md-6 mb-20">
          <label className="d-block" htmlFor="c-email">Email *</label>
          <input id="c-email" name="email" type="email" className="form-control" required autoComplete="email" />
        </div>
        <div className="col-md-6 mb-20">
          <label className="d-block" htmlFor="c-subject">Subject *</label>
          <input id="c-subject" name="subject" className="form-control" required />
        </div>
        <div className="col-12 mb-20">
          <label className="d-block" htmlFor="c-message">Message *</label>
          <textarea id="c-message" name="message" className="form-control" rows={5} required />
        </div>
        <div className="col-12 mb-20">
          <label><input type="checkbox" name="consented" /> Send me news and offers</label>
        </div>
      </div>
      {state.status === "error" && <p className="text-danger" role="alert">{state.message}</p>}
      <button type="submit" className="btn rounded-btn" disabled={state.status === "sending"}>
        {state.status === "sending" ? "Sending..." : "Send message"}
      </button>
    </form>
  );
}
