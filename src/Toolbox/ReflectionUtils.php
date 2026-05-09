<?php

declare(strict_types=1);

/*
 * This file is part of the FormsBundle package.
 *
 * (c) Nicolas Fasano <fasano.nm@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
