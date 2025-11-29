<?php

namespace Fasano\FormsBundle\Reflection;

use ReflectionNamedType;

final class UntypedPropertyType extends ReflectionNamedType
{
    public function getName(): string
    {
        return 'untyped';
    }

    public function isBuiltin(): bool
    {
        return false;
    }

    public function allowsNull(): bool
    {
        return true;
    }

    public function __toString(): string
    {
        return 'untyped';
    }
}