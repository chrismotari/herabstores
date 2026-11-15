import Link from "next/link";
import { JsonLd } from "./JsonLd";
import { breadcrumbJsonLd } from "@/lib/seo";

/** Theme breadcrumb banner + BreadcrumbList structured data. */
export function Breadcrumb({ items }: { items: { name: string; path: string }[] }) {
  const trail = [{ name: "Home", path: "/" }, ...items];

  return (
    <div className="breadcrumb-area breadcrumb-bg-two">
      <JsonLd data={breadcrumbJsonLd(trail)} />
      <div className="container custom-container">
        <div className="row">
          <div className="col-12">
            <div className="breadcrumb-content">
              <nav aria-label="breadcrumb">
                <ol className="breadcrumb">
                  {trail.map((item, index) =>
                    index === trail.length - 1 ? (
                      <li key={item.path} className="breadcrumb-item active" aria-current="page">{item.name}</li>
                    ) : (
                      <li key={item.path} className="breadcrumb-item"><Link href={item.path}>{item.name}</Link></li>
                    ),
                  )}
                </ol>
              </nav>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}
