/** e.g. "15 Nov 2026" */
export function formatDate(value: string | null | undefined): string {
  if (!value) return '';
  return new Intl.DateTimeFormat('en-KE', { day: 'numeric', month: 'short', year: 'numeric' }).format(new Date(value));
}
