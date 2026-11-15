"use client";

import Image from "next/image";
import { useState } from "react";

export function ProductGallery({ name, images }: { name: string; images: string[] }) {
  const [active, setActive] = useState(0);
  const current = images[active] ?? images[0];

  return (
    <div className="shop-details-flex-wrap">
      {images.length > 1 && (
        <div className="shop-details-nav-wrap">
          <ul className="nav nav-tabs" role="tablist">
            {images.map((src, i) => (
              <li key={src} className="nav-item" role="presentation">
                <button
                  type="button"
                  role="tab"
                  aria-selected={i === active}
                  aria-label={`Show image ${i + 1}`}
                  className={`nav-link${i === active ? " active" : ""}`}
                  onClick={() => setActive(i)}
                >
                  <Image src={src} alt="" width={100} height={100} />
                </button>
              </li>
            ))}
          </ul>
        </div>
      )}
      <div className="shop-details-img-wrap">
        <div className="shop-details-img">
          <Image
            src={current}
            alt={name}
            width={600}
            height={600}
            loading="eager"
            fetchPriority="high"
            sizes="(min-width: 992px) 50vw, 100vw"
            style={{ width: "100%", height: "auto" }}
          />
        </div>
      </div>
    </div>
  );
}
