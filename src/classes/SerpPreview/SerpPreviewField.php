<?php

declare(strict_types = 1);

namespace JohannSchopplich\SerpPreview;

use Closure;
use Kirby\Cms\Page;
use Kirby\Cms\Site;
use Kirby\Exception\NotFoundException;
use Kirby\Form\Field\DisplayField;
use Kirby\Http\Url;
use Kirby\Reflection\Attributes\Prop;
use Kirby\Toolkit\Str;

final class SerpPreviewField extends DisplayField
{
    #[Prop(computed: true)]
    protected string|null $faviconUrl = null;

    #[Prop(computed: true)]
    protected string|null $siteTitle = null;

    #[Prop(computed: true)]
    protected string|null $siteUrl = null;

    #[Prop(computed: true)]
    protected string $titleSeparator = '–';

    #[Prop(computed: true)]
    protected string|null $titleContentKey = null;

    #[Prop(computed: true)]
    protected string $defaultTitle = '';

    #[Prop(computed: true)]
    protected string|null $descriptionContentKey = null;

    #[Prop(computed: true)]
    protected string $defaultDescription = '';

    #[Prop(computed: true)]
    protected string|null $searchConsoleUrl = null;

    public function label(): string
    {
        // The blueprint normalizer sets the label to `Str::label()` of the key when
        // none is given, so that value counts as unset.
        if ($this->label === null || $this->label === Str::label($this->name ?? '')) {
            return $this->i18n('johannschopplich.serp-preview.label');
        }

        return parent::label();
    }

    public function faviconUrl(): string|null
    {
        return $this->stringTemplate($this->faviconUrl, safe: false);
    }

    public function siteTitle(): string|null
    {
        return $this->stringTemplate($this->siteTitle, safe: false) ?? $this->kirby()->site()->title()->value();
    }

    public function siteUrl(): string|null
    {
        return $this->stringTemplate($this->siteUrl, safe: false) ?? $this->kirby()->url();
    }

    public function titleSeparator(): string
    {
        return $this->stringTemplate($this->titleSeparator, safe: false);
    }

    public function titleContentKey(): string|null
    {
        return $this->titleContentKey === null ? null : strtolower($this->titleContentKey);
    }

    public function defaultTitle(): string
    {
        return $this->stringTemplate($this->defaultTitle, safe: false);
    }

    public function descriptionContentKey(): string|null
    {
        return $this->descriptionContentKey === null ? null : strtolower($this->descriptionContentKey);
    }

    public function defaultDescription(): string
    {
        return $this->stringTemplate($this->defaultDescription, safe: false);
    }

    public function searchConsoleUrl(): string|null
    {
        return $this->stringTemplate($this->searchConsoleUrl, safe: false);
    }

    /**
     * Tells the Panel whether to send the title and description through the
     * `format` route; a file or user view has no page to hand a formatter, so
     * both stay off there.
     *
     * @return array{title: bool, description: bool}
     */
    public function formatters(): array
    {
        return array_map(
            fn (Closure|null $formatter): bool => $formatter !== null,
            $this->formatterClosures()
        );
    }

    public function previewPath(): string
    {
        $model = $this->model();
        $previewUrl = method_exists($model, 'previewUrl') ? $model->previewUrl() : null;

        if ($previewUrl === null) {
            return '';
        }

        $path = Url::path($previewUrl);
        $installPath = Url::path($this->kirby()->url());

        // Relative to the install, so a subfolder install doesn't show its subfolder twice.
        if (str_starts_with($path . '/', $installPath . '/')) {
            $path = ltrim(substr($path, strlen($installPath)), '/');
        }

        return $path;
    }

    public function props(): array
    {
        return [
            ...parent::props(),
            'formatters' => $this->formatters(),
            'previewPath' => $this->previewPath()
        ];
    }

    public function api(): array
    {
        $page = $this->formatterPage();
        $formatters = $this->formatterClosures();

        return [
            [
                'pattern' => 'format/(title|description)',
                'method' => 'POST',
                'action' => function (string $property) use ($page, $formatters) {
                    if ($page === null) {
                        throw new NotFoundException(message: 'Page not found');
                    }

                    /** @var \Kirby\Api\Api $api */
                    $api = $this;
                    $formatter = $formatters[$property];
                    $value = $api->requestBody('value', '');

                    return [
                        'text' => $formatter === null ? $value : $formatter($value, $page)
                    ];
                }
            ]
        ];
    }

    /**
     * @return array{title: Closure|null, description: Closure|null}
     */
    private function formatterClosures(): array
    {
        $hasPage = $this->formatterPage() !== null;
        $formatters = [];

        foreach (['title', 'description'] as $property) {
            $formatter = $this->kirby()->option('johannschopplich.serp-preview.formatters.' . $property);
            $formatters[$property] = $hasPage && $formatter instanceof Closure ? $formatter : null;
        }

        return $formatters;
    }

    private function formatterPage(): Page|null
    {
        $model = $this->model();

        if ($model instanceof Site) {
            return $model->homePage();
        }

        return $model instanceof Page ? $model : null;
    }
}
