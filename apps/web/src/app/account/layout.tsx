import type { Metadata } from "next";
import { Suspense, type ReactNode } from "react";
import { RequireAuth } from "@/components/auth/RequireAuth";
import { Breadcrumb } from "@/components/Breadcrumb";
import { AccountNav } from "./AccountNav";

export const metadata: Metadata = { title: "My account", robots: { index: false, follow: false } };

export default function AccountLayout({ children }: { children: ReactNode }) {
  return (
    <>
      <Breadcrumb items={[{ name: "My account", path: "/account" }]} />
      <section className="pt-90 pb-90">
        <div className="container">
          {/* The guard and nav read the URL, so they render inside Suspense (Cache Components). */}
          <Suspense fallback={<p>Loading your account...</p>}>
            <RequireAuth>
              <div className="row">
                <div className="col-lg-3 mb-40"><AccountNav /></div>
                <div className="col-lg-9">{children}</div>
              </div>
            </RequireAuth>
          </Suspense>
        </div>
      </section>
    </>
  );
}
