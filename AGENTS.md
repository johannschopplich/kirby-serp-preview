# Kirby SERP Preview

Kirby CMS Panel plugin that renders a live Google search result (SERP) preview of a page, updating as its content is edited.

## Tech Stack

- Panel: Vue 2.7 with Composition API (`<script setup>`, composables)
- Build: kirbyup (Vite-based bundler for Kirby Panel plugins)
- Vue utilities: kirbyuse (provides `usePanel`, `useSection`, `useContent`, etc.)
- Styles: UnoCSS with `presetWind3` (Tailwind v3-compatible utilities, prefixed with `ksp-`)
- PHP: Kirby 4/5 compatible

## Commands

- `pnpm run build` - build the Panel bundle (`index.js` / `index.css`)
- `pnpm run dev` - watch and rebuild during development
- `pnpm run lint` / `pnpm run lint:fix` - lint the Panel source
- `pnpm run test:types` - typecheck with `tsc`

## Entry Points

- Plugin ID: `johannschopplich/serp-preview`
- PHP bootstrap: `index.php` (registers the section, API route, and translations)
- Panel entry: `src/panel/index.ts` (registers the Vue section via `window.panel.plugin()`)
- Section component: `src/panel/components/SerpPreview.vue`
- API route: `src/extensions/api.php`

## Architecture

The Panel entry `src/panel/index.ts` registers a single `serp-preview` section.

PHP extensions in `src/extensions/`:

- `sections.php`: Section props, computed values, and `{{ }}` query resolution (`tryResolveQuery`)
- `api.php`: Authenticated `format/*` route applying optional title/description formatter closures
- `translations.php`: i18n strings (en, de, fr, nl)

## Search Hints

- `window.panel.plugin("johannschopplich/serp-preview"` - Panel registration
- `tryResolveQuery` - resolves `{{ }}` Kirby query placeholders in section props
- `config.formatters` - booleans signalling whether a title/description formatter closure is configured
- `__serp-preview__/format/` - authenticated formatter API route
