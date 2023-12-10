# Kirby SERP Preview

Kirby Panel plugin for search engine result page previews.

## Requirements

- Kirby 4+

Kirby is not a free software. You can try it for free on your local machine but in order to run Kirby on a public server you must purchase a [valid license](https://getkirby.com/buy).

## Installation

### Composer

```bash
composer require johannschopplich/kirby-serp-preview
```

### Download

Download and copy this repository to `/site/plugins/kirby-serp-preview`.

## Getting Started

Create a new section in one of your blueprints:

```yml
sections:
  serp-preview:
    type: serp-preview
    siteTitle: "{{ site.title.value }}"
    siteUrl: "{{ site.url }}"
    titleContentKey: metaTitle
    descriptionContentKey: metaDescription
```

## Configuration

The following options are available:

| Option                  | Type      | Default                    | Description                                                                                                                                                           |
| ----------------------- | --------- | -------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `defaultLanguagePrefix` | `boolean` | `true`                     | Whether to add the language prefix to the `siteUrl` for the default language in multi-language setups.                                                                |
| `faviconUrl`            | `string`  | `null`                     | The URL to the favicon.                                                                                                                                               |
| `siteTitle`             | `string`  | `"{{ site.title.value }}"` | The site title.                                                                                                                                                       |
| `siteUrl`               | `string`  | `"{{ site.url }}"`         | The site URL.                                                                                                                                                         |
| `titleSeparator`        | `string`  | `"-"`                      | The title separator between the page's and the site title. Only applies if no `titleContentKey` is set or as fallback if the text for the `titleContentKey` is empty. |
| `titleContentKey`       | `string`  | `null`                     | The content key for a custom title.                                                                                                                                   |
| `descriptionContentKey` | `string`  | `null`                     | The content key for the page's custom description.                                                                                                                    |
| searchConsoleUrl        | `string`  | `null`                     | If provided, the section will display a link to the Google Search Console.                                                                                            |

## License

[MIT](./LICENSE) License © 2023-PRESENT [Johann Schopplich](https://github.com/johannschopplich)

[MIT](./LICENSE) License © 2023-PRESENT [Dennis Baum](https://github.com/dennisbaum)
