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

use Fasano\PHPrimitives\Contract\Primitive;
use Throwable;

class Introspector
{
    /**
     * @template T of Primitive 
     * @param class-string<T> $fqcn
     * @return ?T
     */
    public static function try(string $fqcn, mixed $value)
    {
        try {
            return $fqcn::construct($value);
        } catch (Throwable) {
            return null;
        }
    }

    /** @param class-string<Primitive> $fqcn */
    public static function error(string $fqcn, mixed $value): ?Throwable
    {
        try {
            $fqcn::construct($value);
            return null;
        } catch (Throwable $e) {
            return $e;
        }
    }

    /** @param class-string<Primitive> $fqcn */
    public static function accepts(string $fqcn, mixed $value): bool
    {
        return Introspector::try($fqcn, $value) !== null;
    }
}