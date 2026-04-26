<?php

declare(strict_types=1);

namespace Fasano\FormsBundle\Tests\Unit\Toolbox;

use Fasano\FormsBundle\Toolbox\StringUtils;
use PHPUnit\Framework\TestCase;

class StringUtilsTest extends TestCase
{
    public function testCamelCase(): void
    {
        self::assertSame('First Name', StringUtils::toTitleCase('firstName'));
    }

    public function testPascalCase(): void
    {
        self::assertSame('Email Address', StringUtils::toTitleCase('EmailAddress'));
    }

    public function testSnakeCase(): void
    {
        self::assertSame('First Name', StringUtils::toTitleCase('first_name'));
    }

    public function testKebabCase(): void
    {
        self::assertSame('First Name', StringUtils::toTitleCase('first-name'));
    }

    public function testSingleWord(): void
    {
        self::assertSame('Email', StringUtils::toTitleCase('email'));
    }

    public function testAlreadyLowercase(): void
    {
        self::assertSame('Full Name', StringUtils::toTitleCase('full name'));
    }
}
