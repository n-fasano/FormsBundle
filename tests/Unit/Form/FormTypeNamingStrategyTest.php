<?php

declare(strict_types=1);

namespace Fasano\FormsBundle\Tests\Unit\Form;

use Fasano\FormsBundle\Form\FormTypeNamingStrategy;
use PHPUnit\Framework\TestCase;

class SomeNamingDto {}

class FormTypeNamingStrategyTest extends TestCase
{
    private FormTypeNamingStrategy $strategy;

    protected function setUp(): void
    {
        $this->strategy = new FormTypeNamingStrategy('Cache\\Forms', 'forms/');
    }

    public function testGetClassNameAppendsSuffix(): void
    {
        self::assertSame('SomeNamingDtoType', $this->strategy->getClassName(SomeNamingDto::class));
    }

    public function testGetNamespacePrefixesSourceNamespace(): void
    {
        $expected = 'Cache\\Forms\\' . __NAMESPACE__;

        self::assertSame($expected, $this->strategy->getNamespace(SomeNamingDto::class));
    }

    public function testGetNamespaceWithEmptyPrefixUsesSourceNamespace(): void
    {
        $strategy = new FormTypeNamingStrategy('', '');

        self::assertSame(__NAMESPACE__, $strategy->getNamespace(SomeNamingDto::class));
    }

    public function testGetFqcn(): void
    {
        $fqcn = $this->strategy->getFqcn(SomeNamingDto::class);

        self::assertSame('Cache\\Forms\\' . __NAMESPACE__ . '\\SomeNamingDtoType', $fqcn);
    }

    public function testGetPath(): void
    {
        $path = $this->strategy->getPath(SomeNamingDto::class);

        self::assertSame('forms/SomeNamingDtoType.php', $path);
    }
}
