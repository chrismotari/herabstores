import type { Metadata } from "next";
import { ContentPage } from "@/components/content/ContentPage";
import { CONTACT } from "@/lib/config";
import { ContactForm } from "./ContactForm";

export const metadata: Metadata = {
  title: "Contact us",
  description: `Questions about an order or our products? Call ${CONTACT.phone} or send us a message.`,
  alternates: { canonical: "/contact-us" },
};

export default function ContactPage() {
  return (
    <ContentPage title="Contact us" path="/contact-us">
      <div className="row">
        <div className="col-lg-7 mb-40">
          <p>Send us a message and one of our team members will get back to you as soon as possible.</p>
          <ContactForm />
        </div>
        <div className="col-lg-5">
          <div className="footer-contact-list">
            <ul>
              <li><div className="icon"><i className="flaticon-place" /></div><p>{CONTACT.address}</p></li>
              <li><div className="icon"><i className="flaticon-telephone-1" /></div><p><a href={CONTACT.phoneHref}>{CONTACT.phone}</a></p></li>
              <li><div className="icon"><i className="flaticon-mail" /></div><p><a href={`mailto:${CONTACT.email}`}>{CONTACT.email}</a></p></li>
              <li><div className="icon"><i className="flaticon-wall-clock" /></div><p>{CONTACT.hours}</p></li>
            </ul>
          </div>
        </div>
      </div>
    </ContentPage>
  );
}
