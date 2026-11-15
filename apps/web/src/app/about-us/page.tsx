import type { Metadata } from "next";
import Link from "next/link";
import { ContentPage } from "@/components/content/ContentPage";
import { CONTACT } from "@/lib/config";

export const metadata: Metadata = {
  title: "About us",
  description: "Herab Groceries brings farm-fresh produce, nuts, grains, herbs and spices to your door in Nairobi.",
  alternates: { canonical: "/about-us" },
};

const FEATURES = [
  { title: "Fresh & local produce", text: "Sourced directly from trusted local farmers to ensure quality and sustainability." },
  { title: "Delivery to your door", text: "Choose your delivery zone at checkout; the delivery fee is shown before you order." },
  { title: "Pay on delivery", text: "No card needed. Pay when your groceries arrive." },
  { title: "Order tracking", text: "Follow your order from confirmation to delivery in your account." },
  { title: "Wide range of products", text: "Fresh fruit & vegetables, nuts & grains, herbs & spices, all carefully sourced." },
  { title: "Friendly support", text: `Our team is available ${CONTACT.hours.replace(" to ", " - ")}.` },
];

export default function AboutPage() {
  return (
    <ContentPage title="About us" path="/about-us">
      <h2 className="h4">Your go-to store for fresh & quality groceries</h2>
      <p>
        Herab Groceries is your destination for farm-fresh produce, premium nuts &amp; grains, and flavourful herbs &amp; spices.
        We are committed to bringing you quality groceries with convenience and affordability.
      </p>
      <p>
        With a focus on freshness and sustainability, we partner with trusted farmers and suppliers so that every product meets
        high standards. Whether you need fresh vegetables, wholesome grains or aromatic spices, we&apos;ve got you covered.
      </p>

      <h2 className="h4 mt-50 mb-30">What we provide</h2>
      <div className="row">
        {FEATURES.map((feature) => (
          <div key={feature.title} className="col-md-6 col-lg-4 mb-30">
            <h3 className="h5">{feature.title}</h3>
            <p className="mb-0">{feature.text}</p>
          </div>
        ))}
      </div>

      <div className="mt-30">
        <Link href="/shop" className="btn rounded-btn">Shop now</Link>
      </div>
    </ContentPage>
  );
}
