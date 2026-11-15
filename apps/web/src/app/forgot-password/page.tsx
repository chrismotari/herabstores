import type { Metadata } from "next";
import { AuthCard } from "@/components/auth/AuthCard";
import { ForgotForm } from "./ForgotForm";

export const metadata: Metadata = { title: "Reset your password", robots: { index: false, follow: true } };

export default function ForgotPasswordPage() {
  return (
    <AuthCard title="Reset your password">
      <ForgotForm />
    </AuthCard>
  );
}
