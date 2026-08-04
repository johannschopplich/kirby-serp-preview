# Kirby SERP Preview

Kirby CMS Panel plugin that renders a live Google search result (SERP) preview of a page, updating as its content is edited.

## Commands

- `composer csfix` – php-cs-fixer, which lives in `tools/phpcs/vendor/bin/`, not `vendor/bin/`
- `pnpm run test:types` – typecheck with `tsc`
- `pnpm run lint` – ESLint
- `pnpm run build` – build the Panel bundle (`index.js` / `index.css`)

There is no PHP test suite in this repo.

## Conventions

- Formatter closures stay on the server. The Panel receives only booleans saying whether a title or description formatter is configured, and calls the `format/*` route to apply them.
- `src/env.d.ts` references `kirbyuse` explicitly: the Panel entry only imports the section component, so the global `Window` augmentation is otherwise absent from the program.
- Comments explain why, not what. In `src/classes/**` a wrapped comment ends with a full stop and a single-line one does not; comments in `src/panel/**` never do.

## Search Hints

- `window.panel.plugin("johannschopplich/serp-preview"` – Panel registration
- `App::plugin(` – PHP plugin registration
- `tryResolveQuery` – resolves `{{ }}` Kirby query placeholders in section props
- `config.formatters` – booleans signalling whether a formatter closure is configured
- `__serp-preview__/format/` – authenticated formatter API route
