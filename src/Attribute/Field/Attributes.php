<?php

declare(strict_types=1);

namespace Fasano\FormsBundle\Attribute\Field;

use Fasano\FormsBundle\Attribute\FieldAttribute;
use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
final readonly class Attributes implements FieldAttribute
{
    public array $value;

    public function __construct(mixed ...$value)
    {
        $this->value = $value;
    }
}