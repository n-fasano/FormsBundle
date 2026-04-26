<?php

declare(strict_types=1);

namespace Fasano\FormsBundle\Attribute\Field;

use Attribute;
use Fasano\FormsBundle\Attribute\FieldAttribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
final readonly class EntryType implements FieldAttribute
{
    public function __construct(
        public string $class,
    ) {}
}
