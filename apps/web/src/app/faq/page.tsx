import type { Metadata } from "next";
import Link from "next/link";
import { ContentPage } from "@/components/content/ContentPage";
import { CONTACT } from "@/lib/config";

export const metadata: Metadata = {
  title: "Delivery, payment & FAQ",
  description: "How delivery, pay on delivery, order tracking and returns work at Herab Groceries.",
  alternates: { canonical: "/faq" },
};

const FAQS: { q: string; a: React.ReactNode }[] = [
  {
    q: "Where do you deliver and how much does it cost?",
    a: <>We deliver within our delivery zones in and around Nairobi. Choose your zone at checkout: its delivery fee is shown before you place your order.</>,
  },
  {
    q: "How do I pay?",
    a: <>You pay on delivery, when your groceries arrive. No card details are needed online.</>,
  },
  {
    q: "Do I need an account to order?",
    a: <>No. You can check out as a guest. With an account you can see your order history and track orders, and we remember your delivery address.</>,
  },
  {
    q: "How do I track my order?",
    a: <>Log in and open <Link href="/account/orders">My orders</Link> to see each order&apos;s status: new, dispatched or delivered.</>,
  },
  {
    q: "Something is wrong with my delivery. What should I do?",
    a: <>Please let us know within 24 hours of receiving your delivery. Call <a href={CONTACT.phoneHref}>{CONTACT.phone}</a> or use our <Link href="/contact-us">contact form</Link>.</>,
  },
  {
    q: "Can I change or cancel an order?",
    a: <>Contact us as soon as possible. Orders can be changed or cancelled until they are dispatched.</>,
  },
];

export default function FaqPage() {
  return (
    <ContentPage title="Delivery, payment & FAQ" path="/faq">
      {FAQS.map((item) => (
        <details key={item.q} className="mb-20">
          <summary className="h5" style={{ cursor: "pointer" }}>{item.q}</summary>
          <p className="mt-10">{item.a}</p>
        </details>
      ))}
    </ContentPage>
  );
}
