"use client";

import Link from "next/link";
import { usePathname, useRouter } from "next/navigation";
import { useSession } from "@/components/auth/SessionProvider";

const LINKS = [
  { href: "/account", label: "Overview" },
  { href: "/account/orders", label: "My orders" },
  { href: "/account/favourites", label: "Favourites" },
  { href: "/account/profile", label: "Profile & password" },
];

export function AccountNav() {
  const pathname = usePathname();
  const router = useRouter();
  const { logout } = useSession();

  return (
    <aside className="shop-sidebar">
      <div className="widget shop-widget">
        <div className="shop-cat-list">
          <ul>
            {LINKS.map((link) => (
              <li key={link.href} className={pathname === link.href ? "active" : undefined}>
                <Link href={link.href} aria-current={pathname === link.href ? "page" : undefined}>{link.label}</Link>
              </li>
            ))}
            <li>
              <button type="button" className="btn-link border-0 bg-transparent p-0" onClick={async () => { await logout(); router.push("/"); }}>
                Log out
              </button>
            </li>
          </ul>
        </div>
      </div>
    </aside>
  );
}
