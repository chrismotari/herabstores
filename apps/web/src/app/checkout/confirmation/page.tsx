import type { Metadata } from "next";
import { Suspense } from "react";
import { Breadcrumb } from "@/components/Breadcrumb";
import { Confirmation } from "./Confirmation";

export const metadata: Metadata = { title: "Order placed", robots: { index: false, follow: false } };

export default function ConfirmationPage() {
  return (
    <>
      <Breadcrumb items={[{ name: "Order placed", path: "/checkout/confirmation" }]} />
      <section className="pt-90 pb-90">
        <div className="container">
          <Suspense fallback={<p>Loading...</p>}>
            <Confirmation />
          </Suspense>
        </div>
      </section>
    </>
  );
}
