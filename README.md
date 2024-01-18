![Kirby Search Engine Result Page Preview](./.github/kirby-serp-preview.png)

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
    # Optional field to override the computed title
    titleContentKey: metaTitle
    descriptionContentKey: metaDescription
```

## Usage

### Page Title

By default, the SERP preview will render the title of the search engine result page preview by connecting the following values with a space:

**Page Title** + **Title Separator** (defaults to `–`) + **Site Title**

The `titleContentKey` section property allows you to define a custom content key for the page's title. If it is set and the field contains a value, it will override the computed title.

## Configuration

The following options are available:

| Option                  | Type              | Default                    | Description                                                                                                                                                                                                                                             |
| ----------------------- | ----------------- | -------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `defaultLanguagePrefix` | Boolean           | `true`                     | Whether to add the language prefix to the `siteUrl` for the default language in multi-language setups.                                                                                                                                                  |
| `faviconUrl`            | String            | `null`                     | The URL to the favicon.                                                                                                                                                                                                                                 |
| `siteTitle`             | String            | `"{{ site.title.value }}"` | The site title.                                                                                                                                                                                                                                         |
| `siteUrl`               | String            | `"{{ site.url }}"`         | The site URL.                                                                                                                                                                                                                                           |
| `titleSeparator`        | String or Boolean | `"-"`                      | The title separator between the page's and the site title. Only applies if no `titleContentKey` is set or as fallback if the text for the `titleContentKey` is empty. To disable the connecting string between model and site title, set it to `false`. |
| `titleContentKey`       | String            | `null`                     | The content key for a custom title.                                                                                                                                                                                                                     |
| `descriptionContentKey` | String            | `null`                     | The content key for the page's custom description.                                                                                                                                                                                                      |
| `searchConsoleUrl`      | String            | `null`                     | If provided, the section will display a link to the Google Search Console.                                                                                                                                                                              |

## License

[MIT](./LICENSE) License © 2023-PRESENT [Johann Schopplich](https://github.com/johannschopplich)

[MIT](./LICENSE) License © 2023-PRESENT [Dennis Baum](https://github.com/dennisbaum)
