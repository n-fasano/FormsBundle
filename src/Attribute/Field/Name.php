<?php

namespace Fasano\FormsBundle\Attribute\Field;

use Fasano\FormsBundle\Attribute\DynamicFormAttribute;
use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_CLASS)]
final readonly class Name implements DynamicFormAttribute
{
    public function __construct(
        public string $value,
    ) {}
}