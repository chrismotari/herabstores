"use client";

import Link from "next/link";
import { useEffect, useState } from "react";
import { useSession } from "@/components/auth/SessionProvider";
import { browserApi } from "@/lib/browser-api";

export function FavouriteButton({ productId, slug }: { productId: string; slug: string }) {
  const { user } = useSession();
  const [saved, setSaved] = useState<boolean | null>(null);

  useEffect(() => {
    if (!user) return;
    void browserApi.GET("/me/favourites").then(({ data }) => setSaved(Boolean(data?.data.some((p) => p.id === productId))));
  }, [user, productId]);

  if (!user) {
    return <Link href={`/login?next=/products/${slug}`} className="fav-btn"><i className="far fa-heart" /> Save to favourites</Link>;
  }

  async function toggle() {
    const next = !saved;
    setSaved(next);
    const { error } = next
      ? await browserApi.POST("/me/favourites", { body: { product_id: productId } })
      : await browserApi.DELETE("/me/favourites/{product}", { params: { path: { product: productId } } });
    if (error) setSaved(!next);
  }

  return (
    <button type="button" className="fav-btn" aria-pressed={Boolean(saved)} onClick={toggle} disabled={saved === null}>
      <i className={saved ? "fas fa-heart" : "far fa-heart"} /> {saved ? "Saved to favourites" : "Save to favourites"}
    </button>
  );
}
