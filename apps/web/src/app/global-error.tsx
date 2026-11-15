"use client";

/** Last-resort error page when the root layout itself fails (no theme CSS available). */
export default function GlobalError({ reset }: { error: Error & { digest?: string }; reset: () => void }) {
  return (
    <html lang="en">
      <body style={{ fontFamily: "system-ui, sans-serif", textAlign: "center", padding: "4rem 1rem" }}>
        <h1>Something went wrong</h1>
        <p>Please try again in a moment.</p>
        <button type="button" onClick={reset}>Try again</button>
      </body>
    </html>
  );
}
