# Kirby SERP Preview

Kirby CMS Panel plugin that renders a live Google search result (SERP) preview of a page, updating as its content is edited.

## Commands

```bash
composer test
composer csfix         # lives in tools/phpcs/vendor/bin/, not vendor/bin/
pnpm run test --run
pnpm run test:types
pnpm run lint
pnpm run build         # after a change in src/panel/; index.js and index.css are committed
```

## Conventions

- Formatter closures stay on the server; the Panel applies them through the `format/*` route.
- `src/panel/site.ts` feeds kirby.tools' Panel mocks.
