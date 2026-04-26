<?php

declare(strict_types=1);

namespace Fasano\FormsBundle\Tests\Unit\Reflection;

use Fasano\FormsBundle\Reflection\CustomPropertyType;
use PHPUnit\Framework\TestCase;

class CustomPropertyTypeTest extends TestCase
{
    public function testGetNameReturnsConstructorValue(): void
    {
        $type = new CustomPropertyType('MyClass');

        self::assertSame('MyClass', $type->getName());
    }

    public function testIsBuiltinAlwaysTrue(): void
    {
        $type = new CustomPropertyType('anything');

        self::assertTrue($type->isBuiltin());
    }

    public function testAllowsNullAlwaysFalse(): void
    {
        $type = new CustomPropertyType('anything');

        self::assertFalse($type->allowsNull());
    }

    public function testToStringReturnsName(): void
    {
        $type = new CustomPropertyType('SomeType');

        self::assertSame('SomeType', (string) $type);
    }
}
