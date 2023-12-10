<?php

use Kirby\Toolkit\I18n;
use Kirby\Query\Query;

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
                        $siteTitle = str_replace(['{{', '}}'], '', $siteTitle);
                        $query = new Query($siteTitle);
                        $siteTitle = $query->resolve();
                    }
                }

                return $siteTitle ?? $kirby->site()->title()->value();
            },
            'siteUrl' => function ($siteUrl = null) {
                /** @var \Kirby\Cms\App $kirby */
                $kirby = $this->kirby();

                if (is_string($siteUrl) && str_starts_with($siteUrl, '{{') && str_ends_with($siteUrl, '}}')) {
                    $siteUrl = str_replace(['{{', '}}'], '', $siteUrl);
                    $query = new Query($siteUrl);
                    $siteUrl = $query->resolve();
                }

                return $siteUrl ?? $kirby->site()->url();
            },
            'titleSeparator' => fn ($titleSeparator = null) => $titleSeparator,
            'titleContentKey' => fn ($titleContentKey = null) => $titleContentKey,
            'descriptionContentKey' => fn ($descriptionContentKey = null) => $descriptionContentKey,
            'searchConsoleUrl' => fn ($searchConsoleUrl = null) => $searchConsoleUrl,
        ]
    ]
];
