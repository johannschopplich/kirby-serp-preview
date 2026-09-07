[![Kirby SERP Preview screenshot](./.github/kirby-serp-preview.png)](https://kirby.tools/serp-preview)

# Kirby SERP Preview

Kirby SERP Preview is a free plugin for [Kirby CMS](https://getkirby.com). It adds a Panel section that draws the Google result snippet for the page being edited – favicon, site name, URL, title and description – and redraws it as you type, before anything is saved.

## Features

- 🖱️ **One Section, Any Page**: Add the section type to a page blueprint – there is nothing else to set up.
- ⚡ **Redraws as You Type**: The snippet follows the open form rather than the saved content, and never writes back.
- 🏷️ **Title and Description Fields**: Name the fields it reads, each with a fallback for when they are empty.
- ✂️ **Formatters**: A closure in your config shortens or cleans the text before the snippet draws it.
- 🧩 **Kirby Queries**: Every display property accepts `{{ ... }}`, resolved against the current model.
- 🔗 **Search Console Link**: An optional button below the snippet, pointing at the URL you configure.
- 🌓 **Light and Dark Mode**: Google's own snippet colors, following the Panel theme.

## Requirements

- Kirby 4 or Kirby 5

## Installation

### Composer (Recommended)

```bash
composer require johannschopplich/kirby-serp-preview
```

### Manual Installation

Download and copy this repository to `/site/plugins/kirby-serp-preview`.

## Documentation

For detailed usage instructions, visit the [Kirby SERP Preview documentation](https://kirby.tools/docs/serp-preview).

## License

[MIT](./LICENSE) License © 2023-PRESENT [Johann Schopplich](https://github.com/johannschopplich)

[MIT](./LICENSE) License © 2023 [Dennis Baum](https://github.com/dennisbaum)
