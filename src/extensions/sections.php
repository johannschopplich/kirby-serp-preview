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

                $defaultConfig = [
                    'parsers' => []
                ];

                // Merge user configuration with defaults
                $config = array_replace_recursive($defaultConfig, $config);

                // Uncheck all all resolver but check if they contain a function
                foreach (['title', 'description'] as $key) {
                    $config['parsers'][$key] = isset($config['parsers'][$key]) && is_callable($config['parsers'][$key]);
                }

                return $config;
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
            }
        ],
        'methods' => [
            'tryResolveQuery' => function ($value, $fallback = null) {
                if (is_string($value)) {
                    // Replace all matches of KQL parts with the query results
                    $value = preg_replace_callback('!\{\{(.+?)\}\}!', function ($matches) {
                        $result = $this->model()->query(trim($matches[1]));
                        return $result ?? '';
                    }, $value);
                }

                return $value ?? $fallback;
            }
        ]
    ]
];
