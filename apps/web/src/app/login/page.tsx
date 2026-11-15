import type { Metadata } from "next";
import { Suspense } from "react";
import { AuthCard } from "@/components/auth/AuthCard";
import { LoginForm } from "./LoginForm";

export const metadata: Metadata = { title: "Log in", robots: { index: false, follow: true } };

export default function LoginPage() {
  return (
    <AuthCard title="Log in">
      <Suspense>
        <LoginForm />
      </Suspense>
    </AuthCard>
  );
}
