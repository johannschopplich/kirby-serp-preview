<?php

use Kirby\Cms\App;
use Kirby\Exception\InvalidArgumentException;
use Kirby\Exception\NotFoundException;

return [
    'routes' => fn (App $kirby) => [
        [
            'pattern' => '__serp-preview__/parse/(:alphanum)',
            'method' => 'POST',
            'action' => function (string $property) use ($kirby) {
                $request = $kirby->request();
                $value = $request->get('value');
                $pageId = $request->get('pageId', $kirby->site()->homePageId());

                if (!in_array($property, ['title', 'description'], true)) {
                    throw new InvalidArgumentException('Invalid key');
                }

                $page = $kirby->page($pageId);

                if (!$page) {
                    throw new NotFoundException('Page not found');
                }

                $parser = $kirby->option('johannschopplich.serp-preview.parsers.' . $property);

                if (!is_callable($parser)) {
                    throw new NotFoundException('Parser not found');
                }

                $text = $parser($value, $page);

                return [
                    'text' => $text
                ];
            }
        ]
    ]
];
