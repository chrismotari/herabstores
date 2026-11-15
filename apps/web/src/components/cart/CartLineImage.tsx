import type { CartItem } from "@herabs/shared";
import Image from "next/image";

const PLACEHOLDER = "/theme/img/product/category_img01.png";

export const lineName = (item: CartItem) => item.product?.name ?? item.basket?.name ?? "Item";
export const lineHref = (item: CartItem) => (item.product ? `/products/${item.product.slug}` : "/shop");
export const lineMax = (item: CartItem) => item.product?.available_quantity ?? item.basket?.available_quantity ?? item.quantity;

export function CartLineImage({ item, size = 80 }: { item: CartItem; size?: number }) {
  const src = item.product?.image_url ?? item.basket?.image_url ?? PLACEHOLDER;
  return <Image src={src} alt={lineName(item)} width={size} height={size} style={{ objectFit: "cover" }} />;
}
