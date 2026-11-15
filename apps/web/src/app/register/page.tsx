import type { Metadata } from "next";
import { Suspense } from "react";
import { AuthCard } from "@/components/auth/AuthCard";
import { RegisterForm } from "./RegisterForm";

export const metadata: Metadata = { title: "Create an account", robots: { index: false, follow: true } };

export default function RegisterPage() {
  return (
    <AuthCard title="Create an account">
      <Suspense>
        <RegisterForm />
      </Suspense>
    </AuthCard>
  );
}
