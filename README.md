[![Kirby SERP Preview](https://kirby.tools/social-card/serp-preview.png)](https://kirby.tools/serp-preview)

# Kirby SERP Preview

Kirby SERP Preview is a plugin for [Kirby CMS](https://getkirby.com). It adds a Panel field that draws the Google result snippet for the page being edited – favicon, site name, URL, title, and description – and redraws it as you type, before anything is saved.

## Features

- 🖱️ **One Field, Any Page**: Add the field type to a page or site blueprint – there is nothing else to set up.
- ⚡ **Redraws as You Type**: The snippet follows the open form rather than the saved content, and never writes back.
- 🏷️ **Title and Description Fields**: Name the fields it reads, each with a fallback for when they are empty.
- ✂️ **Formatters**: Trim or clean the text the way your templates do, so the snippet matches the live page.
- 🧩 **Kirby Queries**: Every property the snippet shows accepts `{{ ... }}`, resolved against the current model.
- 🔗 **Search Console Link**: An optional button in the field header, pointing at the URL you configure.
- 🌓 **Light and Dark Mode**: Google's own snippet colors, following the Panel theme.

## Requirements

- Kirby 6

## Installation

### Composer (Recommended)

```bash
composer require johannschopplich/kirby-serp-preview
```

### Manual Installation

Download and copy this repository to `/site/plugins/kirby-serp-preview`.

## Documentation

For installation, configuration, and usage, see the [Kirby SERP Preview documentation](https://kirby.tools/docs/serp-preview).

## License

[MIT](./LICENSE) License © 2023-PRESENT [Johann Schopplich](https://github.com/johannschopplich)

[MIT](./LICENSE) License © 2023 [Dennis Baum](https://github.com/dennisbaum)
