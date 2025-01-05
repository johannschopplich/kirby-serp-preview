![Kirby Search Engine Result Page Preview screenshot](./.github/kirby-serp-preview.png)

# Kirby SERP Preview

A standalone search engine result page preview to include in any Kirby project. It allows you to preview how your page will look in the search results of Google and other search engines.

> [!TIP]
> New: Ready for Kirby 5! The plugin adapts to the Panel theme and uses Google's dark mode colors if the theme is set to dark.

## Requirements

- Kirby 4 or Kirby 5

Kirby is not free software. However, you can try Kirby and the Starterkit on your local machine or on a test server as long as you need to make sure it is the right tool for your next project. … and when you're convinced, [buy your license](https://getkirby.com/buy).

## Installation

### Composer

```bash
composer require johannschopplich/kirby-serp-preview
```

### Download

Download and copy this repository to `/site/plugins/kirby-serp-preview`.

## Getting Started

Create a new section in one of your blueprints. The following example includes all available options with their respective default values:

```yml
sections:
  serp-preview:
    type: serp-preview
    siteTitle: "{{ site.title.value }}"
    siteUrl: "{{ kirby.url }}"
    # Optional field to override the computed title
    titleContentKey: metaTitle
    # Optional default title as fallback if the field above is empty
    defaultTitle: "{{ page.metaTitle.value }}"
    descriptionContentKey: metaDescription
    # Optional default description as fallback if the field above is empty
    defaultDescription: "{{ site.metaDescription.value }}"
```

## Usage

### Page Title

> [!TIP]
>
> **tl;dr** The title is computed in the following order:
>
> 1. `titleContentKey`
> 2. `defaultTitle`
> 3. Joining the page title, `titleSeparator`, and `siteTitle`.

By default, the SERP preview will render the title of the search engine result page preview by joining the following values with a space:

- 1️⃣ **Page Title**
- 2️⃣ **Title Separator** (defaults to `–`)
- 3️⃣ **Site Title**

However, you can override the title generation above by using the following section properties:

- When `titleContentKey` is set and the corresponding field on the current page is not empty, it will be used as the title.
- When `defaultTitle` is set, it will be used as the title if the `titleContentKey` is empty.

## Configuration

Each configuration option is of type `string`. Some of them support Kirby queries. For example, you can use `{{ site.title.value }}` for the `siteTitle` option.

The following table lists all available options:

| Option                  | Default                    | Queryable | Description                                                                                                                                             |
| ----------------------- | -------------------------- | --------- | ------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `faviconUrl`            | `null`                     | ✅        | The URL to the favicon.                                                                                                                                 |
| `siteTitle`             | `"{{ site.title.value }}"` | ✅        | The site title.                                                                                                                                         |
| `siteUrl`               | `"{{ kirby.url }}"`        | ✅        | The site URL.                                                                                                                                           |
| `titleSeparator`        | `"-"`                      | ✅        | The title separator between the current page title and the site title. Only applies if no `titleContentKey` is set and the target field value is empty. |
| `titleContentKey`       | `null`                     | –         | The content key for a custom title.                                                                                                                     |
| `defaultTitle`          | `null`                     | ✅        | Plain text or Kirby query to use as a fallback instead of 1️⃣2️⃣3️⃣ if the `titleContentKey` is empty.                                                     |
| `descriptionContentKey` | `null`                     | –         | The content key for the page's custom description.                                                                                                      |
| `defaultDescription`    | `null`                     | ✅        | Plain text or Kirby query to use as a fallback if the `descriptionContentKey` is empty.                                                                 |
| `searchConsoleUrl`      | `null`                     | –         | If provided, the section will display a link to the Google Search Console.                                                                              |

## License

[MIT](./LICENSE) License © 2023-PRESENT [Johann Schopplich](https://github.com/johannschopplich)

[MIT](./LICENSE) License © 2023 [Dennis Baum](https://github.com/dennisbaum)
