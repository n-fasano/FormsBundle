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

namespace Fasano\FormsBundle\Attribute\Field;

use Fasano\FormsBundle\Attribute\FieldAttribute;
use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
final class OptGroup implements FieldAttribute
{
    public function __construct(
        public array $provider,
        public ?string $label = null,
        public array $attributes = [],
        public string $optLabel = 'name', // case->name
        public string $optValue = 'value', // case->value
        public bool $isStatic = true,
    ) {}
}