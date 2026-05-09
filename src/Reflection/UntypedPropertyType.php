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

final class UntypedPropertyType extends ReflectionNamedType
{
    public function getName(): string
    {
        return 'untyped';
    }

    public function isBuiltin(): bool
    {
        return true;
    }

    public function allowsNull(): bool
    {
        return true;
    }

    public function __toString(): string
    {
        return 'untyped';
    }
}