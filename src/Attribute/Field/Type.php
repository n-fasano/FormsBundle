<?php

declare(strict_types=1);

namespace Fasano\FormsBundle\Attribute\Field;

use Fasano\FormsBundle\Attribute\FieldAttribute;
use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
final readonly class Type implements FieldAttribute
{
    public function __construct(
        public string $value,
    ) {}
}