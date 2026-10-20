<?php

declare(strict_types = 1);

namespace JohannSchopplich\KirbyTools;

use Kirby\Cms\App;
use Kirby\Cms\Find;
use Kirby\Cms\ModelWithContent;

final class ModelResolver
{
    /**
     * Resolves a model from a model ID: `site`, a page ID or a file ID.
     */
    public static function resolveFromId(string $modelId): ModelWithContent|null
    {
        $kirby = App::instance();

        return $modelId === 'site'
            ? $kirby->site()
            : $kirby->page($modelId, drafts: true) ?? $kirby->file($modelId, drafts: true);
    }

    /**
     * Resolves a model from a Panel view path, e.g. `site`, `pages/xxx` or `pages/xxx/files/yyy`.
     *
     * Returns `null` only for unrecognized path patterns – a recognized path
     * whose model is missing throws instead.
     *
     * @throws \Kirby\Exception\NotFoundException When the matched model does not exist or is not accessible
     */
    public static function resolveFromPath(string $path): ModelWithContent|null
    {
        $kirby = App::instance();

        return match (true) {
            // Covers `account/files/*`, `pages/xxx/files/*`, `site/files/*` and `users/xxx/files/*`.
            preg_match('!(account|pages\/[^\/]+|site|users\/[^\/]+)\/files\/(.+)!', $path, $matches) === 1
                => Find::file($matches[1], $matches[2]),
            str_starts_with($path, 'pages/') => Find::page(substr($path, 6)),
            $path === 'site' => $kirby->site(),
            default => null
        };
    }
}
