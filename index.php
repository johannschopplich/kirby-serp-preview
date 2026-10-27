<?php

use JohannSchopplich\SerpPreview\SerpPreviewField;
use Kirby\Cms\App;

@include_once __DIR__ . '/vendor/autoload.php';

App::plugin('johannschopplich/serp-preview', [
    'fields' => [
        'serp-preview' => SerpPreviewField::class
    ],
    'translations' => require __DIR__ . '/src/extensions/translations.php'
]);
