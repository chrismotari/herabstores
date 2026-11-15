import type { ReactNode } from "react";
import { Breadcrumb } from "@/components/Breadcrumb";

/** Shared shell for static content pages (theme breadcrumb + section). */
export function ContentPage({ title, path, children }: { title: string; path: string; children: ReactNode }) {
  return (
    <>
      <Breadcrumb items={[{ name: title, path }]} />
      <section className="pt-90 pb-90">
        <div className="container">
          <div className="row justify-content-center">
            <div className="col-lg-10">
              <h1 className="h2 mb-30">{title}</h1>
              {children}
            </div>
          </div>
        </div>
      </section>
    </>
  );
}
