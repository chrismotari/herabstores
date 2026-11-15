"use client";

import type { Product } from "@herabs/shared";
import { useEffect, useState } from "react";
import { ProductCard } from "@/components/product/ProductCard";
import { browserApi } from "@/lib/browser-api";

export default function FavouritesPage() {
  const [products, setProducts] = useState<Product[] | null>(null);

  useEffect(() => {
    void browserApi.GET("/me/favourites").then(({ data }) => setProducts(data?.data ?? []));
  }, []);

  if (!products) return <p>Loading favourites...</p>;

  return (
    <div>
      <h1 className="h3 mb-30">Favourites</h1>
      {products.length === 0 ? (
        <p>No favourites yet. Tap the heart on a product to save it here.</p>
      ) : (
        <div className="row">
          {products.map((product) => (
            <div key={product.id} className="col-md-4 col-sm-6"><ProductCard product={product} /></div>
          ))}
        </div>
      )}
    </div>
  );
}
