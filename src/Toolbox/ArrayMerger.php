<?php

// declare(strict_types=1);

namespace Fasano\FormsBundle\Toolbox;

class ArrayMerger
{
    public function __construct(private int $maxDepth = 4)
    {}

    /**
     * Also called `conditional merge`.
     * Prefer values from `$b` over `$a` if they exist and are not `null`.
     */
    public function nonDestructive(array ...$subjects): array
    {
        $base = [];

        foreach ($subjects as $subject) {
            $base = $this->_nonDestructive($base, $subject);
        }

        return $base;
    }

    /**
     * @TODO: use array_replace?
     */
    private function _nonDestructive(array $base, array $override, int $currentDepth = 0): array
    {
        if ($currentDepth >= $this->maxDepth) {
            return $base;
        }

        $result = $base; // Create a copy of $base to avoid modifying the original

        foreach ($override as $key => $value) {
            // Check if the key exists in $result and both are arrays; if so, merge recursively
            if (is_array($value) && isset($result[$key]) && is_array($result[$key])) {
                $result[$key] = self::_nonDestructive($result[$key], $value, $currentDepth + 1);
            }
            // Otherwise, only override if the value is not null
            elseif (null !== $value) {
                $result[$key] = $value;
            }
        }

        return $result;
    }
}
