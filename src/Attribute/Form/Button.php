<?php

namespace Fasano\FormsBundle\Attribute\Form;

use Fasano\FormsBundle\Attribute\DynamicFormAttribute;
use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
final readonly class Button implements DynamicFormAttribute
{
    public array $options;

    public function __construct(mixed ...$options)
    {
        $this->options = $options;
    }
}