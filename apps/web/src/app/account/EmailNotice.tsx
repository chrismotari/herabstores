"use client";

import type { User } from "@herabs/shared";
import { useSearchParams } from "next/navigation";
import { useEffect, useState } from "react";
import { useSession } from "@/components/auth/SessionProvider";
import { browserApi, errorMessage } from "@/lib/browser-api";

/**
 * "Email confirmed" after the verification link (the API redirects here with ?verified=1),
 * or a reminder with a resend button while the address is unconfirmed.
 */
export function EmailNotice() {
  const { user, setUser } = useSession();
  const justVerified = useSearchParams().get("verified") === "1";
  const [status, setStatus] = useState<string | null>(null);

  // The session was loaded before the link was clicked: refresh it so the flag is current.
  useEffect(() => {
    if (!justVerified || user?.email_verified) return;
    void browserApi.GET("/auth/session").then(({ data }) => {
      if (data?.data) setUser(data.data as User);
    });
  }, [justVerified, user?.email_verified, setUser]);

  if (!user) return null;

  if (justVerified) {
    return <p className="alert alert-success" role="status">Thanks, your email address is confirmed.</p>;
  }
  if (user.email_verified) return null;

  async function resend() {
    setStatus("Sending...");
    const { data, error } = await browserApi.POST("/auth/email/verification-notification");
    setStatus(data ? "We've sent you a new link." : errorMessage(error));
  }

  return (
    <div className="alert alert-warning" role="status">
      Please confirm your email address using the link we sent you.{" "}
      {status ?? (
        <a
          href="#resend"
          role="button"
          className="alert-link"
          onClick={(e) => {
            e.preventDefault();
            void resend();
          }}>
          Resend the email
        </a>
      )}
    </div>
  );
}
