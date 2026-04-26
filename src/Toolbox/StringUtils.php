<?php

declare(strict_types=1);

namespace Fasano\FormsBundle\Toolbox;

class StringUtils
{
    public static function toTitleCase(string $input): string
    {
        // Replace all non-alphabetic characters with a space
        $normalized = preg_replace('/[_\-]+/', ' ', $input);

        // Insert space before uppercase letters (for camelCase and PascalCase)
        $spaced = preg_replace('/(?<!^)([A-Z])/', ' $1', $normalized);

        // Convert to lowercase and then to title case
        $titleCased = ucwords(strtolower($spaced));

        return $titleCased;
    }
}
