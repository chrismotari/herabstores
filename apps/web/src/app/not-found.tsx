import Link from "next/link";

export default function NotFound() {
  return (
    <section className="pt-90 pb-90">
      <div className="container text-center">
        <h1 className="h2">Page not found</h1>
        <p>Sorry, we couldn&apos;t find that page. It may have moved, or the product is no longer available.</p>
        <form action="/shop" method="get" role="search" className="d-inline-flex mt-20 mb-30">
          <input type="text" name="q" placeholder="Search products..." aria-label="Search products" className="form-control mr-2" />
          <button type="submit" className="btn">Search</button>
        </form>
        <div>
          <Link href="/" className="btn rounded-btn mr-2">Home</Link>
          <Link href="/shop" className="btn rounded-btn">Shop</Link>
        </div>
      </div>
    </section>
  );
}
