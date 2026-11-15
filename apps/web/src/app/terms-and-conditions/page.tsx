import type { Metadata } from "next";
import { ContentPage } from "@/components/content/ContentPage";

export const metadata: Metadata = {
  title: "Terms and conditions",
  description: "The terms that apply when you shop with Herab Groceries.",
  alternates: { canonical: "/terms-and-conditions" },
};

export default function TermsPage() {
  return (
    <ContentPage title="Terms and conditions" path="/terms-and-conditions">
      <h2 className="h4">Terms of service</h2>
      <p>Herab Groceries is committed to providing high-quality fresh farm produce and related products through our online platform.</p>
      <p>
        By using our website, you agree to comply with the following terms and conditions. These terms govern your use of our
        services, purchases, and any related interactions with our platform. Please read them carefully before proceeding.
      </p>
      <p>All transactions made through Herab Groceries are subject to product availability, pricing accuracy, and compliance with applicable regulations.</p>
      <p>We reserve the right to update our terms and conditions as necessary. Any changes will be communicated through our website.</p>
      <p>
        The use of our platform is subject to responsible shopping practices, and any form of fraudulent or abusive behaviour may
        result in account suspension or legal action.
      </p>

      <h2 className="h4 mt-40">Our sourcing practices</h2>
      <p>
        Herab Groceries sources its products directly from trusted local farmers and suppliers to ensure freshness, quality, and
        sustainability. Our produce is carefully selected and inspected to meet high standards before being delivered to our customers.
      </p>
      <p>
        We prioritise organic and ethically grown products, supporting local communities and eco-friendly farming practices. We work
        closely with our partners to guarantee authenticity and reliability in every purchase.
      </p>

      <h2 className="h4 mt-40">Why choose us?</h2>
      <ul className="list-disc">
        <li>Fresh farm produce sourced from reputable farmers and suppliers.</li>
        <li>A seamless and secure shopping experience.</li>
        <li>Sustainable and ethical sourcing practices.</li>
        <li>Customer satisfaction is our top priority, with dedicated support and service.</li>
      </ul>
    </ContentPage>
  );
}
