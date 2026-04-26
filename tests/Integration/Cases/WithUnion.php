<?php

namespace Fasano\FormsBundle\Tests\Integration\Cases;

final readonly class WithUnion
{
    public function __construct(
        public int|string $something,
    ) {}
}
