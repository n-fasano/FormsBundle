<?php

declare(strict_types=1);

namespace Fasano\FormsBundle\Tests\Unit\Field;

use Fasano\FormsBundle\Field\FieldAnalyzer;
use Fasano\FormsBundle\Field\FieldConfig;
use Fasano\FormsBundle\Field\FieldConfigurator;
use Fasano\FormsBundle\Field\FieldContext;
use Fasano\FormsBundle\FormTypeFactory;
use Fasano\FormsBundle\Tests\Integration\Dto\Email;
use Fasano\PHPrimitives\Contract\Primitive;
use Fasano\PHPrimitives\Contract\Primitive\BooleanPrimitive;
use Fasano\PHPrimitives\Contract\Primitive\FloatPrimitive;
use Fasano\PHPrimitives\Contract\Primitive\IntegerPrimitive;
use PHPUnit\Framework\TestCase;
use ReflectionProperty;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class SomeIntPrimitive implements IntegerPrimitive
{
    public function __construct(public int $value) {}
    public static function construct(int|float|bool|string $value): static { return new static((int) $value); }
    public function deconstruct(): int { return $this->value; }
}

class SomeFloatPrimitive implements FloatPrimitive
{
    public function __construct(public float $value) {}
    public static function construct(int|float|bool|string $value): static { return new static((float) $value); }
    public function deconstruct(): float { return $this->value; }
}

class SomeBoolPrimitive implements BooleanPrimitive
{
    public function __construct(public bool $value) {}
    public static function construct(int|float|bool|string $value): static { return new static((bool) $value); }
    public function deconstruct(): bool { return $this->value; }
}

class SomeUnknownPrimitive implements Primitive
{
    public static function construct(int|float|bool|string $value): static { return new static(); }
    public function deconstruct(): int|float|bool|string { return 0; }
}

class FieldAnalyzerHolder
{
    public string $name;
    public ?string $nullable;
    public Email $email;
    public string|int $union;
    public SomeIntPrimitive $intPrimitive;
    public SomeFloatPrimitive $floatPrimitive;
    public SomeBoolPrimitive $boolPrimitive;
    public SomeUnknownPrimitive $unknownPrimitive;
}

class FieldAnalyzerTest extends TestCase
{
    private FormTypeFactory $factory;

    protected function setUp(): void
    {
        $this->factory = $this->createStub(FormTypeFactory::class);
    }

    private function analyzer(FieldConfigurator ...$configurators): FieldAnalyzer
    {
        return new FieldAnalyzer($configurators, $this->factory);
    }

    public function testUnionTypeThrows(): void
    {
        $this->expectExceptionMessage('Union and intersection types are not supported');

        $this->analyzer()->analyze(new ReflectionProperty(FieldAnalyzerHolder::class, 'union'));
    }

    public function testConfiguratorPipelineIsApplied(): void
    {
        $configurator = $this->createStub(FieldConfigurator::class);
        $configurator->method('configure')->willReturn(new FieldConfig(name: 'piped', type: TextType::class));

        $definition = $this->analyzer($configurator)->analyze(
            new ReflectionProperty(FieldAnalyzerHolder::class, 'name'),
        );

        self::assertSame('piped', $definition->name);
        self::assertSame(TextType::class, $definition->type);
    }

    public function testLaterConfiguratorOverridesEarlier(): void
    {
        $first  = $this->createStub(FieldConfigurator::class);
        $second = $this->createStub(FieldConfigurator::class);
        $first->method('configure')->willReturn(new FieldConfig(name: 'first', type: 'TypeA'));
        $second->method('configure')->willReturn(new FieldConfig(name: 'second', type: 'TypeB'));

        $definition = $this->analyzer($first, $second)->analyze(
            new ReflectionProperty(FieldAnalyzerHolder::class, 'name'),
        );

        self::assertSame('second', $definition->name);
        self::assertSame('TypeB', $definition->type);
    }

    public function testPrimitivePropertyErasesTypeToString(): void
    {
        $configurator = $this->createStub(FieldConfigurator::class);
        $configurator->method('configure')->willReturnCallback(
            function (FieldContext $ctx) {
                return new FieldConfig(name: 'email', type: $ctx->type->getName());
            }
        );

        $definition = $this->analyzer($configurator)->analyze(
            new ReflectionProperty(FieldAnalyzerHolder::class, 'email'),
        );

        // Email extends AbstractString (StringPrimitive) -> type should be erased to 'string'
        self::assertSame('string', $definition->type);
    }

    public function testPrimitivePropertyCreatesTransformer(): void
    {
        $configurator = $this->createStub(FieldConfigurator::class);
        $configurator->method('configure')->willReturn(new FieldConfig(name: 'email', type: 'T'));

        $definition = $this->analyzer($configurator)->analyze(
            new ReflectionProperty(FieldAnalyzerHolder::class, 'email'),
        );

        self::assertNotNull($definition->transformer);
        self::assertStringContainsString('Email', $definition->transformer);
    }

    public function testPrimitiveAttributesAreMergedFromClass(): void
    {
        $capturedAttributes = [];
        $configurator = $this->createStub(FieldConfigurator::class);
        $configurator->method('configure')->willReturnCallback(
            function (FieldContext $ctx) use (&$capturedAttributes) {
                $capturedAttributes = $ctx->attributes;
                return new FieldConfig(name: 'email', type: 'T');
            }
        );

        $this->analyzer($configurator)->analyze(
            new ReflectionProperty(FieldAnalyzerHolder::class, 'email'),
        );

        // Email's class-level attributes (Name, Description, Example from typedocs) should be merged in
        self::assertNotEmpty($capturedAttributes);
    }

    public function testIntegerPrimitiveErasesTypeToInt(): void
    {
        $configurator = $this->createMock(FieldConfigurator::class);
        $configurator->expects(self::once())->method('configure')->willReturnCallback(
            fn (FieldContext $ctx) => new FieldConfig(name: 'v', type: $ctx->type->getName())
        );

        $definition = $this->analyzer($configurator)->analyze(
            new ReflectionProperty(FieldAnalyzerHolder::class, 'intPrimitive'),
        );

        self::assertSame('int', $definition->type);
    }

    public function testFloatPrimitiveErasesTypeToFloat(): void
    {
        $configurator = $this->createMock(FieldConfigurator::class);
        $configurator->expects(self::once())->method('configure')->willReturnCallback(
            fn (FieldContext $ctx) => new FieldConfig(name: 'v', type: $ctx->type->getName())
        );

        $definition = $this->analyzer($configurator)->analyze(
            new ReflectionProperty(FieldAnalyzerHolder::class, 'floatPrimitive'),
        );

        self::assertSame('float', $definition->type);
    }

    public function testBoolPrimitiveErasesTypeToBool(): void
    {
        $configurator = $this->createMock(FieldConfigurator::class);
        $configurator->expects(self::once())->method('configure')->willReturnCallback(
            fn (FieldContext $ctx) => new FieldConfig(name: 'v', type: $ctx->type->getName())
        );

        $definition = $this->analyzer($configurator)->analyze(
            new ReflectionProperty(FieldAnalyzerHolder::class, 'boolPrimitive'),
        );

        self::assertSame('bool', $definition->type);
    }

    public function testUnknownPrimitiveErasesTypeToUntyped(): void
    {
        $configurator = $this->createMock(FieldConfigurator::class);
        $configurator->expects(self::once())->method('configure')->willReturnCallback(
            fn (FieldContext $ctx) => new FieldConfig(name: 'v', type: $ctx->type->getName())
        );

        $definition = $this->analyzer($configurator)->analyze(
            new ReflectionProperty(FieldAnalyzerHolder::class, 'unknownPrimitive'),
        );

        self::assertSame('untyped', $definition->type);
    }
}
