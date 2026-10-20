<?php

declare(strict_types = 1);

namespace JohannSchopplich\KirbyTools;

use Kirby\Form\Field;

final class FieldNormalizer
{
    private const SUPPORTED_TYPES = [
        'blocks', 'checkboxes', 'color', 'date', 'email', 'entries', 'files',
        'gap', 'headline', 'hidden', 'info', 'layout', 'line', 'link', 'list',
        'markdown', 'multiselect', 'number', 'object', 'pages', 'password',
        'radio', 'range', 'select', 'slug', 'stats', 'structure', 'tags', 'tel', 'text',
        'textarea', 'time', 'toggle', 'toggles', 'url', 'users', 'writer',
    ];

    private const MAX_DEPTH = 10;

    private static array|null $supportedTypesMap = null;

    /**
     * Resolves a custom field type to its standard base type
     * by following the `extends` chain.
     */
    public static function resolveBaseType(string $type, int $depth = 0): string
    {
        self::$supportedTypesMap ??= array_flip(self::SUPPORTED_TYPES);

        if (isset(self::$supportedTypesMap[$type]) || $depth >= self::MAX_DEPTH) {
            return $type;
        }

        try {
            $definition = Field::load($type);
        } catch (\Throwable) {
            return $type;
        }

        $extends = $definition['extends'] ?? null;

        if (!is_string($extends) || $extends === '' || $extends === $type) {
            return $type;
        }

        return self::resolveBaseType($extends, $depth + 1);
    }

    /**
     * Normalizes a fields array by resolving custom types and
     * recursing into nested `fields` and `fieldsets[*].tabs[*].fields`.
     */
    public static function normalizeFields(array $fields): array
    {
        foreach ($fields as &$field) {
            if (isset($field['type'])) {
                $field['type'] = self::resolveBaseType($field['type']);
            }

            // `structure` and `object` fields carry their own fields.
            if (isset($field['fields']) && is_array($field['fields'])) {
                $field['fields'] = self::normalizeFields($field['fields']);
            }

            // `blocks` and `layout` fields carry theirs inside fieldset tabs.
            if (isset($field['fieldsets']) && is_array($field['fieldsets'])) {
                foreach ($field['fieldsets'] as &$fieldset) {
                    if (isset($fieldset['tabs']) && is_array($fieldset['tabs'])) {
                        foreach ($fieldset['tabs'] as &$tab) {
                            if (isset($tab['fields']) && is_array($tab['fields'])) {
                                $tab['fields'] = self::normalizeFields($tab['fields']);
                            }
                        }
                    }
                }
            }
        }

        return $fields;
    }
}
