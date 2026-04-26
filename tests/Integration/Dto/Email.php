<?php

namespace Fasano\FormsBundle\Tests\Integration\Dto;

use Fasano\PHPrimitives\AbstractString;
use Fasano\Typedocs\Description;
use Fasano\Typedocs\Example;
use Fasano\Typedocs\Name;
use InvalidArgumentException;

#[Name('Email')]
#[Description('An email address')]
#[Example('john.doe@example.com')]
readonly class Email extends AbstractString
{
    protected static function validate(string $value): void
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException(\sprintf(
                '"%s" is not a valid email',
                $value,
            ));
        }
    }
}