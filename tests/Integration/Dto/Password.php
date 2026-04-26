<?php

namespace Fasano\FormsBundle\Tests\Integration\Dto;

use Fasano\PHPrimitives\Exception\InvalidBackingValue;
use Fasano\PHPrimitives\Contract\Primitive\StringPrimitive;
use Fasano\Typedocs\Description;
use Fasano\Typedocs\Example;
use Fasano\Typedocs\Name;
use SensitiveParameter;

#[Name('Password')]
#[Description('A password with at least 1 uppercase, 1 lowercase, and 1 special character.')]
#[Example('$0O7b#%FWA#Nezxv')]
readonly class Password implements StringPrimitive
{
    final public function __construct(#[SensitiveParameter] public string $value)
    {
        // validate
    }

    final public static function construct(#[SensitiveParameter] mixed $value): static
    {
        if (!\is_string($value)) {
            throw new InvalidBackingValue($value, 'Password');
        }
        
        return new static($value);
    }

    final public function deconstruct(): string
    {
        return $this->value;
    }
}