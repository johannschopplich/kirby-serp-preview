<?php

use JohannSchopplich\Playground\PlaygroundStorage;
use Kirby\Cms\App;
use Kirby\Cms\ModelWithContent;
use Kirby\Content\PlainTextStorage;
use Kirby\Exception\Exception;
use Kirby\Panel\Panel;

load([
    'JohannSchopplich\\Playground\\PlaygroundStorage' => __DIR__ . '/PlaygroundStorage.php'
]);

App::plugin('johannschopplich/playground', [
    'components' => [
        'storage' => function (App $kirby, ModelWithContent $model) {
            if ($kirby->option('debug') === true) {
                return new PlainTextStorage(model: $model);
            }

            return new PlaygroundStorage(model: $model);
        }
    ],
    'hooks' => [
        '*.update:before' => function () {
            if (option('debug') !== true) {
                throw new Exception('Saving changed content to the playground is not allowed. You can only make changes locally.');
            }
        },

        'system.loadPlugins:after' => function () {
            $kirby = App::instance();

            $isDebug = $kirby->option('debug') === true;

            $kirby->extend([
                // The shared demo has no frontend; locally the pages render.
                'routes' => fn (App $kirby) => $isDebug ? [] : [
                    [
                        'pattern' => '(:all)',
                        'action' => function () use ($kirby) {
                            go(Panel::url($kirby->user() === null ? 'login' : 'site'));
                        }
                    ]
                ],
                'areas' => [
                    'login' => fn (App $kirby) => [
                        'views' => [
                            // Auto-login for the playground
                            'login' => [
                                'pattern' => 'login',
                                'auth' => false,
                                'action' => function () use ($kirby, $isDebug) {
                                    if ($kirby->user() === null) {
                                        // Locally `login?role=playground` signs in the
                                        // role without `update` to test what it sees.
                                        $role = $isDebug
                                            ? ($kirby->request()->get('role') ?? 'admin')
                                            : 'playground';
                                        $kirby->users()->role($role)->first()->loginPasswordless([
                                            'long' => true
                                        ]);
                                    }

                                    Panel::go('site');
                                }
                            ]
                        ]
                    ]
                ]
            ], $kirby->plugin('johannschopplich/playground'));
        }
    ]
]);
