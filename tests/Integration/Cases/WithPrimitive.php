<?php

namespace Fasano\FormsBundle\Tests\Integration\Cases;

use Fasano\PHPrimitives\AbstractString;

final readonly class WithPrimitive
{
    public function __construct(
        public Email $email,
    ) {}
}

readonly class Email extends AbstractString
{
    protected static function validate(string $value): void
    {
        // validate email
    }
}