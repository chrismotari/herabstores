"use client";

import Link from "next/link";

export default function Error({ reset }: { error: Error & { digest?: string }; reset: () => void }) {
  return (
    <section className="pt-90 pb-90">
      <div className="container text-center">
        <h1 className="h2">Something went wrong</h1>
        <p>We couldn&apos;t load this page. Please try again in a moment.</p>
        <button type="button" className="btn rounded-btn mr-2" onClick={reset}>Try again</button>
        <Link href="/" className="btn rounded-btn">Home</Link>
      </div>
    </section>
  );
}
