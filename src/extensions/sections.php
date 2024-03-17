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
            'defaultTitle' => fn ($defaultTitle = '') => $defaultTitle,
            'defaultDescription' => fn ($defaultDescription = '') => $defaultDescription,
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

                return $this->tryResolveQuery($this->siteUrl, $kirby->url());
            },
            'titleSeparator' => function () {
                $value = $this->titleSeparator;
                return $this->tryResolveQuery($this->titleSeparator);
            },
            'defaultTitle' => function () {
                return $this->tryResolveQuery($this->defaultTitle);
            },
            'defaultDescription' => function () {
                return $this->tryResolveQuery($this->defaultDescription) ?: $this->tryResolveQuery($this->descriptionFallback);
            },

        ],
        'methods' => [
            'tryResolveQuery' => function ($value, $fallback = null) {
                if (is_string($value)) {
                    $pattern = '/{{(.+?)}}/';

                    // Replace all matches of KQL parts with the query results
                    $value = preg_replace_callback($pattern, function ($matches) {
                        $result = $this->model()->query(trim($matches[1]));
                        return $result !== null ? $result : '';
                    }, $value);
                }

                return $value ?? $fallback;
            }
        ]
    ]
];
