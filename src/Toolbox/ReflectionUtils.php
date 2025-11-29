<?php

// declare(strict_types=1);

namespace Fasano\FormsBundle\Toolbox;

use LogicException;
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
     * @param class-string<T> $attributeName
     *
     * @return T
     */
    public static function getAttribute(ReflectionClass|ReflectionProperty $reflection, string $attributeName)
    {
        return self::findAttribute($reflection, $attributeName)
            ?? throw new LogicException("Attributes {$attributeName} does not exist.");
    }

    /**
     * @template T
     *
     * @param class-string<T> $attributeName
     *
     * @return ?T
     */
    public static function findAttribute(ReflectionClass|ReflectionProperty $reflection, string $attributeName)
    {
        $attributes = $reflection->getAttributes($attributeName);

        foreach ($attributes as $attribute) {
            if ($attribute->getName() === $attributeName) {
                return $attribute->newInstance();
            }
        }

        return null;
    }

    /**
     * @template T
     *
     * @param class-string<T> $attributeName
     *
     * @return T[]
     */
    public static function findAttributes(ReflectionClass|ReflectionProperty $reflection, string $attributeName): array
    {
        $attributes = [];

        foreach ($reflection->getAttributes($attributeName) as $attribute) {
            $attributes[] = $attribute->newInstance();
        }

        return $attributes;
    }
}
