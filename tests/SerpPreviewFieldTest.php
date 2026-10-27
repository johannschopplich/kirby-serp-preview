<?php

declare(strict_types = 1);

use JohannSchopplich\SerpPreview\SerpPreviewField;
use Kirby\Cms\App;
use Kirby\Cms\Page;
use Kirby\Toolkit\Str;
use PHPUnit\Framework\Attributes\PreserveGlobalState;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[RunTestsInSeparateProcesses]
#[PreserveGlobalState(false)]
final class SerpPreviewFieldTest extends TestCase
{
    #[Test]
    public function props_carry_resolved_queries_lowercased_content_keys_and_the_preview_path(): void
    {
        $app = $this->bootApp([
            'site' => [
                'content' => ['title' => 'Example'],
                'children' => [
                    ['slug' => 'about', 'content' => ['title' => 'About', 'metaTitle' => 'About Meta']]
                ]
            ]
        ]);
        $props = $this->field($app, [
            'defaultTitle' => '{{ site.title }} · {{ page.metaTitle }}',
            'titleContentKey' => 'SEOTitle',
            'titleSeparator' => '|'
        ])->toArray();

        $this->assertSame([
            'defaultTitle' => 'Example · About Meta',
            'previewPath' => 'about',
            'siteTitle' => 'Example',
            'titleContentKey' => 'seotitle',
            'titleSeparator' => '|'
        ], array_intersect_key($props, array_flip([
            'defaultTitle',
            'previewPath',
            'siteTitle',
            'titleContentKey',
            'titleSeparator'
        ])));
    }

    #[Test]
    public function label_falls_back_to_the_plugin_translation_unless_the_blueprint_sets_one(): void
    {
        $app = $this->bootApp([]);

        $this->assertSame('SERP Preview', $this->field($app, [
            'name' => 'serpPreview',
            'label' => Str::label('serpPreview')
        ])->label());
        $this->assertSame('SEO', $this->field($app, ['label' => 'SEO'])->label());
    }

    #[Test]
    public function previewPath_leaves_out_the_subfolder_of_a_subfolder_install(): void
    {
        $app = $this->bootApp([]);

        $this->assertSame('about', $this->field($app)->previewPath());
    }

    #[Test]
    public function previewPath_keeps_the_language_prefix(): void
    {
        $app = $this->bootApp([
            'languages' => [
                ['code' => 'en', 'default' => true, 'url' => '/en']
            ]
        ]);

        $this->assertSame('en/about', $this->field($app)->previewPath());
    }

    #[Test]
    public function api_hands_the_title_formatter_the_value_and_the_edited_page(): void
    {
        $response = $this->bootFormatterApp()->api()->call('pages/about/fields/serp/format/title', 'POST', [
            'body' => ['value' => 'Hello']
        ]);

        $this->assertSame('Hello · about', $response['text']);
    }

    #[Test]
    public function api_hands_the_title_formatter_the_home_page_on_the_site_view(): void
    {
        $response = $this->bootFormatterApp()->api()->call('site/fields/serp/format/title', 'POST', [
            'body' => ['value' => 'Hello']
        ]);

        $this->assertSame('Hello · home', $response['text']);
    }

    private function bootFormatterApp(): App
    {
        $fields = ['serp' => ['type' => 'serp-preview']];

        return $this->bootApp([
            'blueprints' => [
                'pages/default' => ['fields' => $fields],
                'site' => ['fields' => $fields]
            ],
            'options' => [
                'api.allowImpersonation' => true,
                'johannschopplich.serp-preview' => [
                    'formatters' => [
                        'title' => fn (string $value, Page $page): string => $value . ' · ' . $page->slug()
                    ]
                ]
            ],
            'site' => [
                'children' => [
                    ['slug' => 'about'],
                    ['slug' => 'home']
                ]
            ]
        ]);
    }

    private function bootApp(array $props): App
    {
        $app = new App([
            'roots' => ['index' => '/dev/null'],
            'urls' => ['index' => 'https://example.com/sub'],
            'site' => [
                'children' => [
                    ['slug' => 'about']
                ]
            ],
            ...$props
        ]);

        // Kirby only hands out a preview URL to a user with the `preview` permission.
        $app->impersonate('kirby');

        return $app;
    }

    private function field(App $app, array $props = []): SerpPreviewField
    {
        return new SerpPreviewField(...[
            'model' => $app->page('about'),
            'name' => 'serp',
            'type' => 'serp-preview',
            ...$props
        ]);
    }
}
