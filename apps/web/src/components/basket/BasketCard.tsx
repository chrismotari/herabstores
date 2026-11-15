import { formatPrice, type Basket } from "@herabs/shared";
import Image from "next/image";
import Link from "next/link";
import { AddToCart } from "@/components/cart/AddToCart";

const PLACEHOLDER = "/theme/img/product/discount_img01.jpg";

export function BasketCard({ basket, eager = false }: { basket: Basket; eager?: boolean }) {
  const href = `/baskets/${basket.slug}`;

  return (
    <div className="sp-product-item">
      <div className="sp-product-thumb">
        <Link href={href}>
          <Image
            src={basket.image_url || PLACEHOLDER}
            alt={basket.name}
            width={300}
            height={300}
            sizes="(min-width: 1200px) 25vw, (min-width: 768px) 33vw, 50vw"
            loading={eager ? "eager" : "lazy"}
            style={{ width: "100%", height: "auto", objectFit: "cover", aspectRatio: "1 / 1" }}
          />
        </Link>
      </div>
      <div className="sp-product-content">
        <h3 className="title h6"><Link href={href}>{basket.name}</Link></h3>
        <span className="product-status">{formatPrice(basket.price)}</span>
        <AddToCart basketId={basket.id} maxQuantity={basket.available_quantity} />
      </div>
    </div>
  );
}
