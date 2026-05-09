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

namespace Fasano\FormsBundle\Reflection;

use ReflectionNamedType;

final class CustomPropertyType extends ReflectionNamedType
{
    public function __construct(private string $__name)
    {}

    public function getName(): string
    {
        return $this->__name;
    }

    public function isBuiltin(): bool
    {
        return true;
    }

    public function allowsNull(): bool
    {
        return false;
    }

    public function __toString(): string
    {
        return $this->__name;
    }
}