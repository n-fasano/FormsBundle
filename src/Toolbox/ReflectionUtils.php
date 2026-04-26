<?php

declare(strict_types=1);

namespace Fasano\FormsBundle\Toolbox;

use ReflectionClass;
use ReflectionProperty;

class ReflectionUtils
{
    /**
     * @template T
     *
     * @param class-string<T> $attributeName
     */
    public static function hasAttribute(ReflectionClass|ReflectionProperty $reflection, string $attributeName): bool
    {
        return null !== self::findAttribute($reflection, $attributeName);
    }

    /**
     * @template T
     *
     * @param class-string<T> $attributeFqcn
     *
     * @return ?T
     */
    public static function findAttribute(ReflectionClass|ReflectionProperty $reflection, string $attributeFqcn)
    {
        $attributes = $reflection->getAttributes($attributeFqcn);

        return ($attributes[0] ?? null)?->newInstance();
    }
}
