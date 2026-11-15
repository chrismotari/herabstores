"use client";

import { formatPrice, type DeliveryZone, type Order } from "@herabs/shared";
import Link from "next/link";
import { useRouter } from "next/navigation";
import { useState, type FormEvent } from "react";
import { useSession } from "@/components/auth/SessionProvider";
import { useCart } from "@/components/cart/CartProvider";
import { lineName } from "@/components/cart/CartLineImage";
import { DiscountForm } from "@/components/cart/DiscountForm";
import { browserApi } from "@/lib/browser-api";

export const LAST_ORDER_KEY = "herabs.lastOrder";

type Errors = Record<string, string[]>;

export function CheckoutForm({ zones }: { zones: DeliveryZone[] }) {
  const router = useRouter();
  const { cart, loading, refresh } = useCart();
  const { user } = useSession();
  const [zoneChoice, setZoneId] = useState("");
  const [errors, setErrors] = useState<Errors>({});
  const [submitting, setSubmitting] = useState(false);

  // Logged-in customers start with their saved zone
  const zoneId = zoneChoice || user?.delivery_zone_id || "";

  const items = cart?.items ?? [];
  const zone = zones.find((z) => z.id === zoneId);
  const subtotal = cart?.subtotal ?? 0;
  const discount = cart?.discount && !cart.discount.error ? cart.discount.amount : 0;
  const fieldError = (name: string) => errors[name]?.[0];

  async function onSubmit(event: FormEvent<HTMLFormElement>) {
    event.preventDefault();
    const form = new FormData(event.currentTarget);
    const value = (name: string) => {
      const v = String(form.get(name) ?? "").trim();
      return v === "" ? null : v;
    };

    setSubmitting(true);
    setErrors({});
    const { data, error } = await browserApi.POST("/orders", {
      body: {
        fname: value("fname") ?? "",
        lname: value("lname") ?? "",
        phone: value("phone") ?? "",
        alt_phone: value("alt_phone"),
        email: value("email"),
        address: value("address") ?? "",
        city: value("city") ?? "",
        delivery_zone_id: zoneId,
        message: value("message"),
        consented: form.get("consented") === "on",
        accept_terms: form.get("accept_terms") === "on",
      } as never,
    });
    setSubmitting(false);

    if (data) {
      const order: Order = data.data;
      try {
        sessionStorage.setItem(LAST_ORDER_KEY, JSON.stringify(order));
      } catch {
        // storage unavailable: the confirmation page falls back to the order number
      }
      await refresh();
      router.push(`/checkout/confirmation?order=${encodeURIComponent(order.order_number)}`);
      return;
    }

    const body = error as { errors?: Errors; message?: string } | undefined;
    setErrors(body?.errors ?? { form: [body?.message ?? "Something went wrong. Please try again."] });
  }

  if (loading) return <p>Loading checkout...</p>;
  if (items.length === 0) {
    return (
      <div className="text-center">
        <h1 className="h3">Your cart is empty</h1>
        <Link href="/shop" className="btn rounded-btn mt-20">Start shopping</Link>
      </div>
    );
  }

  const input = (name: string, label: string, props: React.InputHTMLAttributes<HTMLInputElement> = {}) => (
    <div className="col-md-6 mb-20">
      <label className="form-label" htmlFor={`co-${name}`}>{label}{props.required && <span className="text-danger ml-1">*</span>}</label>
      <input id={`co-${name}`} name={name} className={`form-control${fieldError(name) ? " is-invalid" : ""}`} {...props} />
      {fieldError(name) && <div className="invalid-feedback">{fieldError(name)}</div>}
    </div>
  );

  return (
    <form onSubmit={onSubmit} noValidate={false}>
      <div className="row justify-content-center">
        <div className="col-lg-7">
          <div className="checkout-form-wrap">
            <div className="checkout-form-top">
              <h1 className="title h5">Delivery information</h1>
              {!user && <p>Have an account? <Link href="/login?next=/checkout">Log in</Link> to use your saved details.</p>}
            </div>
            {/* key: re-mount with the user's details once they load */}
            <div className="row" key={user?.id ?? "guest"}>
              {input("fname", "First name", { required: true, autoComplete: "given-name", defaultValue: user?.fname })}
              {input("lname", "Last name", { required: true, autoComplete: "family-name", defaultValue: user?.lname })}
              {input("phone", "Phone", { required: true, type: "tel", autoComplete: "tel", defaultValue: user?.phone })}
              {input("alt_phone", "Alternative phone", { type: "tel" })}
              {input("email", "Email (for your receipt)", { type: "email", autoComplete: "email", defaultValue: user?.email })}
              {input("city", "Town / city", { required: true, autoComplete: "address-level2", defaultValue: user?.city ?? "Nairobi" })}
              <div className="col-12 mb-20">
                <label className="form-label" htmlFor="co-address">Delivery address<span className="text-danger ml-1">*</span></label>
                <textarea id="co-address" name="address" rows={2} required autoComplete="street-address"
                  className={`form-control${fieldError("address") ? " is-invalid" : ""}`} defaultValue={user?.address ?? ""} />
                {fieldError("address") && <div className="invalid-feedback">{fieldError("address")}</div>}
              </div>
              <div className="col-12 mb-20">
                <label className="form-label" htmlFor="co-zone">Delivery zone<span className="text-danger ml-1">*</span></label>
                <select id="co-zone" required value={zoneId} onChange={(e) => setZoneId(e.target.value)}
                  className={`form-control${fieldError("delivery_zone_id") ? " is-invalid" : ""}`}>
                  <option value="" disabled>Choose your area...</option>
                  {zones.map((z) => (
                    <option key={z.id} value={z.id}>{z.name}: {formatPrice(z.delivery_fee)}</option>
                  ))}
                </select>
                {zone?.description && <small className="d-block mt-1">{zone.description}</small>}
                {fieldError("delivery_zone_id") && <div className="invalid-feedback d-block">{fieldError("delivery_zone_id")}</div>}
              </div>
              <div className="col-12 mb-20">
                <label className="form-label" htmlFor="co-message">Delivery notes</label>
                <textarea id="co-message" name="message" rows={2} className="form-control" placeholder="Gate code, landmarks, best time..." />
              </div>
            </div>
          </div>
        </div>

        <div className="col-lg-5">
          <div className="shop-cart-total order-summary-wrap">
            <h2 className="title h3">Your order</h2>
            <ul>
              {items.map((item) => (
                <li key={item.id} className="d-flex justify-content-between">
                  <span>{item.quantity} x {lineName(item)}</span>
                  <span>{formatPrice(item.line_total)}</span>
                </li>
              ))}
              <li className="sub-total"><span>Subtotal</span> {formatPrice(subtotal)}</li>
              {discount > 0 && <li><span>Discount ({cart?.discount?.code})</span> -{formatPrice(discount)}</li>}
              <li><span>Delivery</span> {zone ? formatPrice(zone.delivery_fee) : "Choose a zone"}</li>
              <li className="cart-total-amount">
                <span>Total (pay on delivery)</span>
                <span className="amount">{formatPrice(subtotal - discount + (zone?.delivery_fee ?? 0))}</span>
              </li>
            </ul>
            <div className="mb-10"><label><input type="checkbox" name="consented" /> Send me news and offers</label></div>
            <div className="mb-20">
              <label>
                <input type="checkbox" name="accept_terms" required /> I accept the <Link href="/terms-and-conditions" target="_blank">terms and conditions</Link>
              </label>
              {fieldError("accept_terms") && <div className="text-danger small">{fieldError("accept_terms")}</div>}
            </div>
            <DiscountForm />
            {(errors.cart || errors.form || errors.discount_code) && (
              <div className="alert alert-danger" role="alert">
                {[...(errors.cart ?? []), ...(errors.discount_code ?? []), ...(errors.form ?? [])].map((m) => <div key={m}>{m}</div>)}
              </div>
            )}
            <button type="submit" className="btn" disabled={submitting || !zoneId}>
              {submitting ? "Placing order..." : "Place order"}
            </button>
          </div>
        </div>
      </div>
    </form>
  );
}
