![Kirby Search Engine Result Page Preview](./.github/kirby-serp-preview.png)

# Kirby SERP Preview

> [!NOTE]
> Used v0.x before? Upgrading is easy! Please read to the [migration guide](#migration) to learn more about the changes in v1.0.

A standalone search engine result page preview to include in any Kirby project. It allows you to preview how your page will look in the search results of Google and other search engines.

## Requirements

- Kirby 4+

Kirby is not free software. However, you can try Kirby and the Starterkit on your local machine or on a test server as long as you need to make sure it is the right tool for your next project. … and when you’re convinced, [buy your license](https://getkirby.com/buy).

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
    # Optional default title as fallback if the field above is empty
    defaultTitle: "{{ page.getMetaTitle.value }}"
    descriptionContentKey: metaDescription
    # Optional default description as fallback if the field above is empty
    defaultDescription: "{{ site.metaDescription.value }}"
```

## Usage

### Page Title

By default, the SERP preview will render the title of the search engine result page preview by joining the following values with a space:

- 1️⃣ **Page Title**
- 2️⃣ **Title Separator** (defaults to `–`)
- 3️⃣ **Site Title**

> [!TIP]
> When `defaultTitle` is defined, it will be used as a fallback if the `titleContentKey` is empty and thus override the computed title.

The `titleContentKey` section property allows you to define a custom content key for the page's title. If it is set and the field contains a value, it will override the computed title.

## Configuration

Each configuration option is of type `string`. Some of them support Kirby queries. For example, you can use `{{ site.title.value }}` for the `siteTitle` option.

The following table lists all available options:

| Option                  | Default                    | Queryable | Description                                                                                                                                             |
| ----------------------- | -------------------------- | --------- | ------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `faviconUrl`            | `null`                     | ✅        | The URL to the favicon.                                                                                                                                 |
| `siteTitle`             | `"{{ site.title.value }}"` | ✅        | The site title.                                                                                                                                         |
| `siteUrl`               | `"{{ site.url }}"`         | ✅        | The site URL.                                                                                                                                           |
| `titleSeparator`        | `"-"`                      | ✅        | The title separator between the current page title and the site title. Only applies if no `titleContentKey` is set and the target field value is empty. |
| `titleContentKey`       | `null`                     | –         | The content key for a custom title.                                                                                                                     |
| `defaultTitle`          | `null`                     | ✅        | Plain text or Kirby query to use as a fallback instead of 1️⃣2️⃣3️⃣ if the `titleContentKey` is empty.                                                     |
| `descriptionContentKey` | `null`                     | –         | The content key for the page's custom description.                                                                                                      |
| `defaultDescription`    | `null`                     | ✅        | Plain text or Kirby query to use as a fallback if the `descriptionContentKey` is empty.                                                                 |
| `searchConsoleUrl`      | `null`                     | –         | If provided, the section will display a link to the Google Search Console.                                                                              |

## Migration

Each major release might introduce breaking changes, new features, or deprecate old ones. The migration guide will help you to understand what has changed and how to adapt your project to the new version.

### From v0.x to v1.0

- The `descriptionFallback` section property has been renamed to `defaultDescription`. Please update your blueprints accordingly.

## License

[MIT](./LICENSE) License © 2023-PRESENT [Johann Schopplich](https://github.com/johannschopplich)

[MIT](./LICENSE) License © 2023-PRESENT [Dennis Baum](https://github.com/dennisbaum)
