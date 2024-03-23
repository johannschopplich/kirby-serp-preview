<?php

use Kirby\Cms\App;
use Kirby\Exception\InvalidArgumentException;
use Kirby\Exception\NotFoundException;

return [
    'routes' => fn (App $kirby) => [
        [
            'pattern' => '__serp-preview__/format/(:alphanum)',
            'method' => 'POST',
            'action' => function (string $property) use ($kirby) {
                $request = $kirby->request();
                $pageId = $request->get('pageId', $kirby->site()->homePageId());
                $value = $request->get('value', '');

                if (!in_array($property, ['title', 'description'], true)) {
                    throw new InvalidArgumentException('Invalid key');
                }

                $page = $kirby->page($pageId);

                if (!$page) {
                    throw new NotFoundException('Page not found');
                }

                $formatter = $kirby->option('johannschopplich.serp-preview.formatters.' . $property);

                if (!is_callable($formatter)) {
                    throw new NotFoundException('Formatter not found');
                }

                $text = $formatter($value, $page);

                return [
                    'text' => $text
                ];
            }
        ]
    ]
];
