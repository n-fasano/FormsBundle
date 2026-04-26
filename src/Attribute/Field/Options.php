<?php

declare(strict_types=1);

namespace Fasano\FormsBundle\Attribute\Field;

use Fasano\FormsBundle\Attribute\FieldAttribute;
use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
final readonly class Options implements FieldAttribute
{
    public array $value;

    public function __construct(mixed ...$value)
    {
        $this->value = $value;
    }
}