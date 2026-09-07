<?php

use Kirby\Toolkit\Str;

return [
    'debug' => true,

    'content' => [
        'locking' => false
    ],

    'johannschopplich.serp-preview' => [
        'formatters' => [
            // Google cuts a title around 60 characters and a description around 160.
            'title' => fn (string $value) => Str::short($value, 60),
            'description' => fn (string $value) => Str::short(strip_tags($value), 160)
        ]
    ],

    'panel' => [
        'vue' => [
            'compiler' => false
        ]
    ]
];
