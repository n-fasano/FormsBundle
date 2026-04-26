<?php

declare(strict_types=1);

namespace Fasano\FormsBundle\Tests\Unit\Toolbox;

use Fasano\FormsBundle\Attribute\Form\Options;
use Fasano\FormsBundle\Toolbox\ReflectionUtils;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

#[Options(foo: 'bar')]
class ReflectionUtilsTaggedClass {}

class ReflectionUtilsUntaggedClass {}

class ReflectionUtilsTest extends TestCase
{
    public function testFindAttributeReturnsInstanceWhenPresent(): void
    {
        $result = ReflectionUtils::findAttribute(new ReflectionClass(ReflectionUtilsTaggedClass::class), Options::class);

        self::assertInstanceOf(Options::class, $result);
        self::assertSame(['foo' => 'bar'], $result->value);
    }

    public function testFindAttributeReturnsNullWhenAbsent(): void
    {
        $result = ReflectionUtils::findAttribute(new ReflectionClass(ReflectionUtilsUntaggedClass::class), Options::class);

        self::assertNull($result);
    }

    public function testHasAttributeReturnsTrueWhenPresent(): void
    {
        self::assertTrue(ReflectionUtils::hasAttribute(new ReflectionClass(ReflectionUtilsTaggedClass::class), Options::class));
    }

    public function testHasAttributeReturnsFalseWhenAbsent(): void
    {
        self::assertFalse(ReflectionUtils::hasAttribute(new ReflectionClass(ReflectionUtilsUntaggedClass::class), Options::class));
    }
}
