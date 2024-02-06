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
                $value = $this->siteTitle;

                /** @var \Kirby\Cms\App */
                $kirby = $this->kirby();
                /** @var \Kirby\Cms\Page */
                $model = $this->model();

                if (is_string($value) && str_starts_with($value, '{{') && str_ends_with($value, '}}')) {
                    $value = $model->query(substr($value, 2, -2));
                }

                return $value ?? $kirby->site()->title()->value();
            },
            'siteUrl' => function () {
                $value = $this->siteUrl;

                /** @var \Kirby\Cms\App */
                $kirby = $this->kirby();
                /** @var \Kirby\Cms\Page */
                $model = $this->model();

                if (is_string($value) && str_starts_with($value, '{{') && str_ends_with($value, '}}')) {
                    $value = $model->query(substr($value, 2, -2));
                }

                return $value ?? $kirby->site()->url();
            },
            'titleSeparator' => function () {
                $value = $this->titleSeparator;

                /** @var \Kirby\Cms\Page */
                $model = $this->model();

                if (is_string($value) && str_starts_with($value, '{{') && str_ends_with($value, '}}')) {
                    $value = $model->query(substr($value, 2, -2));
                }

                return $value;
            },
            'descriptionFallback' => function () {
                $value = $this->descriptionFallback;

                /** @var \Kirby\Cms\Page */
                $model = $this->model();

                if (is_string($value) && str_starts_with($value, '{{') && str_ends_with($value, '}}')) {
                    $value = $model->query(substr($value, 2, -2));
                }

                return $value;
            }
        ]
    ]
];
