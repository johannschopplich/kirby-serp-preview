<?php

use Kirby\Cms\App;

App::plugin('johannschopplich/serp-preview', [
    'sections' => require __DIR__ . '/src/extensions/sections.php'
]);
