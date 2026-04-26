<?php

namespace Fasano\FormsBundle\Tests\Integration\Cases;

use Fasano\FormsBundle\Attribute\Field;

final readonly class WithAttributes
{
    public function __construct(
        #[Field\Attributes(class: 'bg-blue')]
        public string $name,
    ) {}
}
