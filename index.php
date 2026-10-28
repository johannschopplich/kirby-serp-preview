<?php

use JohannSchopplich\SerpPreview\SerpPreviewField;
use Kirby\Cms\App;
use Kirby\Exception\LogicException;

if (version_compare(App::version() ?? '0', '6.0.0-beta', '<')) {
    throw new LogicException('Kirby SERP Preview requires Kirby 6');
}

@include_once __DIR__ . '/vendor/autoload.php';

App::plugin('johannschopplich/serp-preview', [
    'fields' => [
        'serp-preview' => SerpPreviewField::class
    ],
    'translations' => require __DIR__ . '/src/extensions/translations.php'
]);
