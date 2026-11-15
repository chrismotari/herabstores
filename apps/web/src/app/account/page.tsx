"use client";

import Link from "next/link";
import { useSession } from "@/components/auth/SessionProvider";
import { EmailNotice } from "./EmailNotice";

export default function AccountOverview() {
  const { user } = useSession();
  if (!user) return null;

  return (
    <div>
      <EmailNotice />
      <h1 className="h3">Hello, {user.fname}!</h1>
      <p>From here you can follow your orders, manage favourites and update your details.</p>
      <div className="row mt-30">
        <div className="col-md-6 mb-30">
          <h2 className="h5">Contact</h2>
          <p className="mb-0">{user.fname} {user.lname}<br />{user.email}<br />{user.phone}</p>
        </div>
        <div className="col-md-6 mb-30">
          <h2 className="h5">Default delivery address</h2>
          <p className="mb-0">{user.address ? <>{user.address}<br />{user.city}</> : "Saved at your first checkout."}</p>
        </div>
      </div>
      <Link href="/account/orders" className="btn rounded-btn mr-2">My orders</Link>
      <Link href="/account/profile" className="btn rounded-btn">Edit details</Link>
    </div>
  );
}
