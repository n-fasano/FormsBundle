<?php

declare(strict_types=1);

namespace Fasano\FormsBundle\Tests\Unit\Toolbox;

use Fasano\FormsBundle\Tests\Integration\Dto\Email;
use Fasano\FormsBundle\Toolbox\Introspector;
use PHPUnit\Framework\TestCase;

class IntrospectorTest extends TestCase
{
    public function testTryReturnsInstanceOnValidValue(): void
    {
        $result = Introspector::try(Email::class, 'test@example.com');

        self::assertInstanceOf(Email::class, $result);
    }

    public function testTryReturnsNullOnInvalidValue(): void
    {
        $result = Introspector::try(Email::class, 'not-an-email');

        self::assertNull($result);
    }

    public function testAcceptsTrueOnValidValue(): void
    {
        self::assertTrue(Introspector::accepts(Email::class, 'test@example.com'));
    }

    public function testAcceptsFalseOnInvalidValue(): void
    {
        self::assertFalse(Introspector::accepts(Email::class, 'not-an-email'));
    }

    public function testErrorReturnsNullOnValidValue(): void
    {
        self::assertNull(Introspector::error(Email::class, 'test@example.com'));
    }

    public function testErrorReturnsThrowableOnInvalidValue(): void
    {
        $result = Introspector::error(Email::class, 'not-an-email');

        self::assertInstanceOf(\Throwable::class, $result);
    }
}
