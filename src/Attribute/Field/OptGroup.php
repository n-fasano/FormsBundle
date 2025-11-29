<?php

namespace Fasano\FormsBundle\Attribute\Field;

use Fasano\FormsBundle\Attribute\DynamicFormAttribute;
use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
final class OptGroup implements DynamicFormAttribute
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