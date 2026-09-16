<?php

namespace JohannSchopplich\Playground;

use Kirby\Cms\Language;
use Kirby\Content\PlainTextStorage;
use Kirby\Content\VersionId;

/**
 * Custom storage handler that prevents the `changes` version
 * from being persisted to disk. This ensures that unsaved
 * Panel changes from one user are not visible to others
 * when sharing the same Panel user account.
 */
class PlaygroundStorage extends PlainTextStorage
{
    public function delete(VersionId $versionId, Language $language): void
    {
        if ($versionId->is('changes')) {
            return;
        }

        parent::delete($versionId, $language);
    }

    public function exists(VersionId $versionId, Language $language): bool
    {
        if ($versionId->is('changes')) {
            return false;
        }

        return parent::exists($versionId, $language);
    }

    public function modified(VersionId $versionId, Language $language): int|null
    {
        if ($versionId->is('changes')) {
            return null;
        }

        return parent::modified($versionId, $language);
    }

    public function read(VersionId $versionId, Language $language): array
    {
        if ($versionId->is('changes')) {
            return [];
        }

        return parent::read($versionId, $language);
    }

    public function touch(VersionId $versionId, Language $language): void
    {
        if ($versionId->is('changes')) {
            return;
        }

        parent::touch($versionId, $language);
    }

    protected function write(VersionId $versionId, Language $language, array $fields): void
    {
        if ($versionId->is('changes')) {
            return;
        }

        parent::write($versionId, $language, $fields);
    }
}
