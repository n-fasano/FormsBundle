<?php

namespace Fasano\FormsBundle\Tests\Integration\Cases;

use Fasano\FormsBundle\Attribute\Field;

final readonly class WithName
{
    public function __construct(
        #[Field\Name('name')]
        public string $username,
    ) {}
}
