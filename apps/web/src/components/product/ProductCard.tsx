import { formatPrice, type Product } from "@herabs/shared";
import Image from "next/image";
import Link from "next/link";
import { AddToCart } from "@/components/cart/AddToCart";

const PLACEHOLDER = "/theme/img/product/category_img01.png";

export function ProductCard({ product }: { product: Product }) {
  const href = `/products/${product.slug}`;

  return (
    <div className="sp-product-item">
      <div className="sp-product-thumb">
        <Link href={href}>
          <Image
            src={product.image_url || PLACEHOLDER}
            alt={product.name}
            width={300}
            height={300}
            sizes="(min-width: 1200px) 25vw, (min-width: 768px) 33vw, 50vw"
            style={{ width: "100%", height: "auto", objectFit: "cover", aspectRatio: "1 / 1" }}
          />
        </Link>
      </div>
      <div className="sp-product-content">
        <h6 className="title"><Link href={href}>{product.name}</Link></h6>
        <span className="product-status">{formatPrice(product.price)}</span>
        {product.stock_status !== "In Stock" && <p className="small mb-1">{product.stock_status}</p>}
        <AddToCart productId={product.id} maxQuantity={product.available_quantity} />
      </div>
    </div>
  );
}

export function ProductGrid({ products }: { products: Product[] }) {
  if (products.length === 0) return <p>No products found.</p>;

  return (
    <div className="row justify-content-center">
      {products.map((product) => (
        <div key={product.id} className="col-xl-3 col-md-4 col-sm-6">
          <ProductCard product={product} />
        </div>
      ))}
    </div>
  );
}
