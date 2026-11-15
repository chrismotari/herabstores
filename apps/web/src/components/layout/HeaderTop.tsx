import Link from "next/link";
import { CONTACT } from "@/lib/config";

export function HeaderTop() {
  return (
    <div className="header-top-wrap">
      <div className="container custom-container">
        <div className="row align-items-center">
          <div className="col-md-7">
            <div className="header-top-left">
              <ul>
                <li className="header-top-currency">KSH - Kenyan Shilling</li>
                <li className="header-work-time">
                  Working time: <span>{CONTACT.hours.replace(" to ", " - ")}</span>
                </li>
              </ul>
            </div>
          </div>
          <div className="col-md-5">
            <div className="header-top-right">
              <ul>
                <li><Link href="/about-us">About Us</Link></li>
                <li><Link href="/contact-us">Contact Us</Link></li>
                <li><Link href="/faq">FAQ</Link></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}
