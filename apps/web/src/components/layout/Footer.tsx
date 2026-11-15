import Image from "next/image";
import Link from "next/link";
import { cacheLife } from "next/cache";
import { CONTACT } from "@/lib/config";
import { NewsletterForm } from "./NewsletterForm";

export function Footer() {
  return (
    <footer>
      <div className="footer-area gray-bg pt-80 pb-30">
        <div className="container">
          <div className="row justify-content-between">
            <div className="col-xl-3 col-lg-4 col-md-6">
              <div className="footer-widget mb-50">
                <div className="footer-logo mb-25">
                  <Link href="/"><Image src="/theme/img/logo/logo.png" alt="Herab Groceries" width={177} height={45} /></Link>
                </div>
                <div className="footer-contact-list">
                  <ul>
                    <li><div className="icon"><i className="flaticon-place" /></div><p>{CONTACT.address}</p></li>
                    <li>
                      <div className="icon"><i className="flaticon-telephone-1" /></div>
                      <h5 className="number"><a href={CONTACT.phoneHref}>{CONTACT.phone}</a></h5>
                    </li>
                    <li><div className="icon"><i className="flaticon-mail" /></div><p><a href={`mailto:${CONTACT.email}`}>{CONTACT.email}</a></p></li>
                    <li><div className="icon"><i className="flaticon-wall-clock" /></div><p>{CONTACT.hours}</p></li>
                  </ul>
                </div>
              </div>
            </div>
            <div className="col-xl-2 col-lg-3 col-md-4 col-sm-6">
              <div className="footer-widget mb-50">
                <div className="fw-title"><h5 className="title">Customer Service</h5></div>
                <div className="fw-link">
                  <ul>
                    <li><Link href="/cart">Cart</Link></li>
                    <li><Link href="/account/orders">Track my Order</Link></li>
                    <li><Link href="/account/orders">My Orders</Link></li>
                    <li><Link href="/faq">Delivery &amp; Payment</Link></li>
                  </ul>
                </div>
              </div>
            </div>
            <div className="col-xl-2 col-lg-3 col-md-4 col-sm-6">
              <div className="footer-widget mb-50">
                <div className="fw-title"><h5 className="title">Useful Links</h5></div>
                <div className="fw-link">
                  <ul>
                    <li><Link href="/about-us">About us</Link></li>
                    <li><Link href="/terms-and-conditions">Terms and Conditions</Link></li>
                    <li><Link href="/contact-us">Contact Us</Link></li>
                    <li><Link href="/faq">FAQ</Link></li>
                  </ul>
                </div>
              </div>
            </div>
            <div className="col-xl-4 col-lg-4 col-md-6">
              <div className="footer-widget footer-box-widget mb-50">
                <div className="f-newsletter">
                  <div className="fw-title"><h5 className="title">Newsletter</h5></div>
                  <NewsletterForm />
                  <p>Be notified when we have new deals</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div className="copyright-wrap">
        <div className="container">
          <div className="row align-items-center">
            <div className="col-md-6">
              <div className="copyright-text">
                <p>Copyright &copy; <CurrentYear /> <a href="https://saibadigital.tech" target="_blank" rel="noopener">Saiba Digital Solutions</a></p>
              </div>
            </div>
            <div className="col-md-6">
              <div className="payment-accepted text-center text-md-right">
                <span>Pay on delivery</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </footer>
  );
}

async function CurrentYear() {
  "use cache";
  cacheLife("days");
  return new Date().getFullYear();
}
