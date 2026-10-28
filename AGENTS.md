# Kirby SERP Preview

Kirby CMS Panel plugin that renders a live Google search result (SERP) preview of a page, updating as its content is edited.

## Commands

```bash
composer test          # PHPUnit
composer csfix         # php-cs-fixer, lives in tools/phpcs/vendor/bin/, not vendor/bin/
pnpm run test --run    # Vitest
pnpm run test:types    # typecheck with vue-tsc
pnpm run lint          # ESLint
pnpm run build         # build the Panel bundle (index.js / index.css)
```

## Conventions

- Formatter closures stay on the server. The Panel receives only booleans saying whether a title or description formatter is configured, and calls the `format/*` route to apply them.
- `src/panel/site.ts` feeds kirby.tools' Panel mocks.
