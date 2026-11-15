import type { ReactNode } from "react";

/** Centered card for login/register/password pages. */
export function AuthCard({ title, children }: { title: string; children: ReactNode }) {
  return (
    <section className="pt-90 pb-90">
      <div className="container">
        <div className="row justify-content-center">
          <div className="col-md-8 col-lg-6 col-xl-5">
            <div className="shop-cart-total">
              <h1 className="title h3">{title}</h1>
              {children}
            </div>
          </div>
        </div>
      </div>
    </section>
  );
}

export function Field({
  name,
  label,
  error,
  ...props
}: { name: string; label: string; error?: string } & React.InputHTMLAttributes<HTMLInputElement>) {
  return (
    <div className="mb-20">
      <label className="form-label" htmlFor={`f-${name}`}>{label}</label>
      <input id={`f-${name}`} name={name} className={`form-control${error ? " is-invalid" : ""}`} {...props} />
      {error && <div className="invalid-feedback">{error}</div>}
    </div>
  );
}
