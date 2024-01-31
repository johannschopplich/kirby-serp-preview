<?php

use Kirby\Query\Query;
use Kirby\Toolkit\I18n;

return [
    'serp-preview' => [
        'props' => [
            'label' => fn ($label = null) => I18n::translate($label, $label),
            'defaultLanguagePrefix' => fn ($defaultLanguagePrefix = null) => $defaultLanguagePrefix,
            'faviconUrl' => fn ($faviconUrl = null) => $faviconUrl,
            'siteTitle' => function ($siteTitle = null) {
                /** @var \Kirby\Cms\App $kirby */
                $kirby = $this->kirby();

                if (is_string($siteTitle)) {
                    if (str_starts_with($siteTitle, '{{') && str_ends_with($siteTitle, '}}')) {
                        $query = new Query(substr($siteTitle, 2, -2));
                        $siteTitle = $query->resolve();
                    }
                }

                return $siteTitle ?? $kirby->site()->title()->value();
            },
            'siteUrl' => function ($siteUrl = null) {
                /** @var \Kirby\Cms\App $kirby */
                $kirby = $this->kirby();

                if (is_string($siteUrl) && str_starts_with($siteUrl, '{{') && str_ends_with($siteUrl, '}}')) {
                    $query = new Query(substr($siteUrl, 2, -2));
                    $siteUrl = $query->resolve();
                }

                return $siteUrl ?? $kirby->site()->url();
            },
            'titleSeparator' => function ($titleSeparator = null) {
                if (is_string($titleSeparator) && str_starts_with($titleSeparator, '{{') && str_ends_with($titleSeparator, '}}')) {
                    $query = new Query(substr($titleSeparator, 2, -2));
                    $titleSeparator = $query->resolve();
                }

                return $titleSeparator ?? '–';
            },
            'titleContentKey' => fn ($titleContentKey = null) => $titleContentKey,
            'descriptionContentKey' => fn ($descriptionContentKey = null) => $descriptionContentKey,
            'descriptionFallback' => function ($descriptionFallback = null) {
                if (is_string($descriptionFallback) && str_starts_with($descriptionFallback, '{{') && str_ends_with($descriptionFallback, '}}')) {
                    $query = new Query(substr($descriptionFallback, 2, -2));
                    $descriptionFallback = $query->resolve();
                }

                return $descriptionFallback ?? '';
            },
            'searchConsoleUrl' => fn ($searchConsoleUrl = null) => $searchConsoleUrl
        ]
    ]
];
