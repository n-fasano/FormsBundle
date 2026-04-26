<?php

namespace Fasano\FormsBundle\Tests\Integration\Cases;

use Fasano\PHPrimitives\AbstractString;

use Fasano\Typedocs\Description;
use Fasano\Typedocs\Example;
use Fasano\Typedocs\Name;

final readonly class WithTypedocs
{
    public function __construct(
        public IpAddress $ip,
    ) {}
}

#[Name('IP')]
#[Description('An IPv4 address')]
#[Example('0.0.0.0')]
readonly class IpAddress extends AbstractString
{
    protected static function validate(string $value): void
    {
        // validate IP address
    }
}