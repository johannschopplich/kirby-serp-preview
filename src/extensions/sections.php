<?php

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
            'descriptionContentKey' => fn ($descriptionContentKey = null) => is_string($descriptionContentKey) ? strtolower($descriptionContentKey) : $descriptionContentKey,
            'descriptionFallback' => fn ($descriptionFallback = '') => $descriptionFallback,
            'searchConsoleUrl' => fn ($searchConsoleUrl = null) => $searchConsoleUrl
        ],
        'computed' => [
            'siteTitle' => function () {
                /** @var \Kirby\Cms\App */
                $kirby = $this->kirby();

                return $this->tryResolveQuery($this->siteTitle, $kirby->site()->title()->value());
            },
            'siteUrl' => function () {
                /** @var \Kirby\Cms\App */
                $kirby = $this->kirby();

                return $this->tryResolveQuery($this->siteUrl, $kirby->site()->url());
            },
            'titleSeparator' => function () {
                $value = $this->titleSeparator;
                return $this->tryResolveQuery($this->titleSeparator);
            },
            'descriptionFallback' => function () {
                return $this->tryResolveQuery($this->descriptionFallback);
            }
        ],
        'methods' => [
            'tryResolveQuery' => function ($value, $fallback = null) {
                if (is_string($value) && str_starts_with($value, '{{') && str_ends_with($value, '}}')) {
                    return $this->model()->query(substr($value, 2, -2));
                }

                return $value ?? $fallback;
            }
        ]
    ]
];
