<?php

declare(strict_types=1);

namespace Fasano\FormsBundle\Reflection;

use ReflectionNamedType;

final class CustomPropertyType extends ReflectionNamedType
{
    public function __construct(private string $__name)
    {}

    public function getName(): string
    {
        return $this->__name;
    }

    public function isBuiltin(): bool
    {
        return true;
    }

    public function allowsNull(): bool
    {
        return false;
    }

    public function __toString(): string
    {
        return $this->__name;
    }
}