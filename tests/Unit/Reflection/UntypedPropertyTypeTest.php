<?php

declare(strict_types=1);

namespace Fasano\FormsBundle\Tests\Unit\Reflection;

use Fasano\FormsBundle\Reflection\UntypedPropertyType;
use PHPUnit\Framework\TestCase;

class UntypedPropertyTypeTest extends TestCase
{
    public function testGetNameReturnsUntyped(): void
    {
        $type = new UntypedPropertyType();

        self::assertSame('untyped', $type->getName());
    }

    public function testIsBuiltinAlwaysTrue(): void
    {
        $type = new UntypedPropertyType();

        self::assertTrue($type->isBuiltin());
    }

    public function testAllowsNullAlwaysTrue(): void
    {
        $type = new UntypedPropertyType();

        self::assertTrue($type->allowsNull());
    }

    public function testToStringReturnsUntyped(): void
    {
        $type = new UntypedPropertyType();

        self::assertSame('untyped', (string) $type);
    }
}
