/** Payload returned by the section's `load()` endpoint. */
export interface SectionData {
  label?: string;
  config?: { formatters?: { title?: boolean; description?: boolean } };
  faviconUrl?: string;
  siteTitle?: string;
  siteUrl?: string;
  titleSeparator?: string;
  searchConsoleUrl?: string;
  previewUrl?: string;
  titleContentKey?: string;
  defaultTitle?: string;
  descriptionContentKey?: string;
  defaultDescription?: string;
}
