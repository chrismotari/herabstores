import Link from "next/link";

/** Theme pagination. `hrefFor(page)` builds each link, keeping the current filters. */
export function Pagination({ page, lastPage, hrefFor }: { page: number; lastPage: number; hrefFor: (page: number) => string }) {
  if (lastPage <= 1) return null;

  const pages = Array.from({ length: lastPage }, (_, i) => i + 1).filter(
    (p) => p === 1 || p === lastPage || Math.abs(p - page) <= 2,
  );

  return (
    <nav className="pagination-wrap" aria-label="Pagination">
      <ul>
        {page > 1 && <li className="prev"><Link href={hrefFor(page - 1)} rel="prev">Prev</Link></li>}
        {pages.map((p, i) => (
          <li key={p} className={p === page ? "active" : undefined}>
            {i > 0 && p - pages[i - 1] > 1 && <span className="mr-2">...</span>}
            <Link href={hrefFor(p)} aria-current={p === page ? "page" : undefined}>{p}</Link>
          </li>
        ))}
        {page < lastPage && <li className="next"><Link href={hrefFor(page + 1)} rel="next">Next</Link></li>}
      </ul>
    </nav>
  );
}
