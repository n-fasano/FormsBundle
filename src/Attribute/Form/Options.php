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

namespace Fasano\FormsBundle\Attribute\Form;

use ArrayAccess;
use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
final class Options implements ArrayAccess 
{
    public array $value;

    public function __construct(mixed ...$value)
    {
        $this->value = $value;
    }

    public function offsetExists(mixed $offset): bool
    {
        return isset($this->value[$offset]);
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->value[$offset];
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->value[$offset] = $value;
    }

    public function offsetUnset(mixed $offset): void
    {
        unset($this->value[$offset]);
    }
}