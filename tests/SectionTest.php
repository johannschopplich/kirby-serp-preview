<?php

declare(strict_types = 1);

use Kirby\Cms\App;
use Kirby\Cms\Section;
use PHPUnit\Framework\Attributes\PreserveGlobalState;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[RunTestsInSeparateProcesses]
#[PreserveGlobalState(false)]
final class SectionTest extends TestCase
{
    #[Test]
    public function previewPath_leaves_out_the_subfolder_of_a_subfolder_install(): void
    {
        $app = $this->bootApp([]);

        $this->assertSame('about', $this->section($app)->previewPath());
    }

    #[Test]
    public function previewPath_keeps_the_language_prefix(): void
    {
        $app = $this->bootApp([
            'languages' => [
                ['code' => 'en', 'default' => true, 'url' => '/en']
            ]
        ]);

        $this->assertSame('en/about', $this->section($app)->previewPath());
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

    private function section(App $app): Section
    {
        return new Section('serp-preview', [
            'name' => 'serp',
            'model' => $app->page('about')
        ]);
    }
}
