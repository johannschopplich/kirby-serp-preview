<?php

use Closure;
use Kirby\Toolkit\I18n;

return [
    'serp-preview' => [
        'props' => [
            'label' => fn ($label = null) => I18n::translate($label, $label),
            'faviconUrl' => fn ($faviconUrl = null) => $faviconUrl,
            'siteTitle' => fn ($siteTitle = null) => $siteTitle,
            'siteUrl' => fn ($siteUrl = null) => $siteUrl,
            'titleSeparator' => fn ($titleSeparator = '–') => $titleSeparator,
            'titleContentKey' => fn ($titleContentKey = null) => is_string($titleContentKey) ? strtolower($titleContentKey) : $titleContentKey,
            'defaultTitle' => fn ($defaultTitle = '') => $defaultTitle,
            'descriptionContentKey' => fn ($descriptionContentKey = null) => is_string($descriptionContentKey) ? strtolower($descriptionContentKey) : $descriptionContentKey,
            'defaultDescription' => fn ($defaultDescription = '') => $defaultDescription,
            'searchConsoleUrl' => fn ($searchConsoleUrl = null) => $searchConsoleUrl
        ],
        'computed' => [
            'config' => function () {
                /** @var \Kirby\Cms\App */
                $kirby = $this->kirby();
                $config = $kirby->option('johannschopplich.serp-preview', []);

                if (!is_array($config)) {
                    $config = [];
                }

                $formatters = $config['formatters'] ?? [];

                return [
                    'formatters' => [
                        'title' => ($formatters['title'] ?? null) instanceof Closure,
                        'description' => ($formatters['description'] ?? null) instanceof Closure
                    ]
                ];
            },
            'faviconUrl' => function () {
                return $this->tryResolveQuery($this->faviconUrl);
            },
            'siteTitle' => function () {
                /** @var \Kirby\Cms\App */
                $kirby = $this->kirby();

                return $this->tryResolveQuery($this->siteTitle, $kirby->site()->title()->value());
            },
            'siteUrl' => function () {
                /** @var \Kirby\Cms\App */
                $kirby = $this->kirby();

                return $this->tryResolveQuery($this->siteUrl, $kirby->url());
            },
            'titleSeparator' => function () {
                return $this->tryResolveQuery($this->titleSeparator);
            },
            'defaultTitle' => function () {
                return $this->tryResolveQuery($this->defaultTitle);
            },
            'defaultDescription' => function () {
                return $this->tryResolveQuery($this->defaultDescription);
            },
            'searchConsoleUrl' => function () {
                return $this->tryResolveQuery($this->searchConsoleUrl);
            },
            'previewUrl' => function () {
                $model = $this->model();

                return method_exists($model, 'previewUrl') ? $model->previewUrl() : null;
            }
        ],
        'methods' => [
            'tryResolveQuery' => function ($value, $fallback = null) {
                if (is_string($value)) {
                    // Replace all matches of KQL parts with the query results
                    $value = preg_replace_callback('!\{\{(.+?)\}\}!', function ($matches) {
                        $result = $this->model()->query(trim($matches[1]));

                        return is_scalar($result) || $result instanceof \Stringable ? (string)$result : '';
                    }, $value);
                }

                return $value ?? $fallback;
            }
        ]
    ]
];
