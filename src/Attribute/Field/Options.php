<?php

namespace Fasano\FormsBundle\Attribute\Field;

use Fasano\FormsBundle\Attribute\DynamicFormAttribute;
use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_CLASS)]
final readonly class Options implements DynamicFormAttribute
{
    public array $value;

    public function __construct(mixed ...$value)
    {
        $this->value = $value;
    }
}