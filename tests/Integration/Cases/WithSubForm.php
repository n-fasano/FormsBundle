<?php

namespace Fasano\FormsBundle\Tests\Integration\Cases;

final readonly class WithSubForm
{
    public function __construct(
        public SubForm $subForm,
    ) {}
}


final readonly class SubForm
{
    public function __construct(
        public string $field,
    ) {}
}