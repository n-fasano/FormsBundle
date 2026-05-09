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

namespace Fasano\FormsBundle\Field;

use Fasano\FormsBundle\FormTypeFactory;
use ReflectionNamedType;
use ReflectionProperty;

final class FieldContext
{
    /**
     * @param object[] $attributes Instantiated attribute objects from the property (and its primitive class, if applicable)
     */
    public function __construct(
        public readonly ReflectionNamedType $type,
        public readonly array $attributes,
        public readonly FormTypeFactory $factory,
        public readonly ReflectionProperty $property,
    ) {}
}