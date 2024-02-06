<?php

use Kirby\Toolkit\I18n;

return [
    'serp-preview' => [
        'props' => [
            'label' => fn ($label = null) => I18n::translate($label, $label),
            'defaultLanguagePrefix' => fn ($defaultLanguagePrefix = null) => $defaultLanguagePrefix,
            'faviconUrl' => fn ($faviconUrl = null) => $faviconUrl,
            'siteTitle' => fn ($siteTitle = null) => $siteTitle,
            'siteUrl' => fn ($siteUrl = null) => $siteUrl,
            'titleSeparator' => fn ($titleSeparator = '–') => $titleSeparator,
            'titleContentKey' => fn ($titleContentKey = null) => $titleContentKey,
            'descriptionContentKey' => fn ($descriptionContentKey = null) => $descriptionContentKey,
            'descriptionFallback' => fn ($descriptionFallback = '') => $descriptionFallback,
            'searchConsoleUrl' => fn ($searchConsoleUrl = null) => $searchConsoleUrl
        ],
        'computed' => [
            'siteTitle' => function () {
                $siteTitle = $this->siteTitle;

                /** @var \Kirby\Cms\App */
                $kirby = $this->kirby();
                /** @var \Kirby\Cms\Page */
                $model = $this->model();

                if (is_string($siteTitle) && str_starts_with($siteTitle, '{{') && str_ends_with($siteTitle, '}}')) {
                    $siteTitle = $model->query(substr($siteTitle, 2, -2));
                }

                return $siteTitle ?? $kirby->site()->title()->value();
            },
            'siteUrl' => function () {
                $siteUrl = $this->siteUrl;

                /** @var \Kirby\Cms\App */
                $kirby = $this->kirby();
                /** @var \Kirby\Cms\Page */
                $model = $this->model();

                if (is_string($siteUrl) && str_starts_with($siteUrl, '{{') && str_ends_with($siteUrl, '}}')) {
                    $siteUrl = $model->query(substr($siteUrl, 2, -2));
                }

                return $siteUrl ?? $kirby->site()->url();
            },
            'titleSeparator' => function () {
                $titleSeparator = $this->titleSeparator;

                /** @var \Kirby\Cms\Page */
                $model = $this->model();

                if (is_string($titleSeparator) && str_starts_with($titleSeparator, '{{') && str_ends_with($titleSeparator, '}}')) {
                    $titleSeparator = $model->query(substr($titleSeparator, 2, -2));
                }

                return $titleSeparator;
            },
            'descriptionFallback' => function () {
                $descriptionFallback = $this->descriptionFallback;

                /** @var \Kirby\Cms\Page */
                $model = $this->model();

                if (is_string($descriptionFallback) && str_starts_with($descriptionFallback, '{{') && str_ends_with($descriptionFallback, '}}')) {
                    $descriptionFallback = $model->query(substr($descriptionFallback, 2, -2));
                }

                return $descriptionFallback;
            }
        ]
    ]
];
