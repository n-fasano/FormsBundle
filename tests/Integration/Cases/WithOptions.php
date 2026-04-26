<?php

namespace Fasano\FormsBundle\Tests\Integration\Cases;

use Fasano\FormsBundle\Attribute\Field;

final readonly class WithOptions
{
    public function __construct(
        #[Field\Options(help: 'A helpful note')]
        public string $name,
    ) {}
}
