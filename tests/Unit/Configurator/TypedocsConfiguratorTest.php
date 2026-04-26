<?php

declare(strict_types=1);

namespace Fasano\FormsBundle\Tests\Unit\Configurator;

use Fasano\FormsBundle\Attribute\Field\Name as FieldName;
use Fasano\FormsBundle\Configurator\Typedoc\TypedocConfigurator;
use Fasano\FormsBundle\Configurator\TypedocsConfigurator;
use Fasano\FormsBundle\Field\FieldConfig;
use Fasano\FormsBundle\Field\FieldContext;
use Fasano\FormsBundle\FormTypeFactory;
use Fasano\FormsBundle\Reflection\UntypedPropertyType;
use Fasano\Typedocs;
use PHPUnit\Framework\TestCase;
use ReflectionProperty;

class TypedocsConfiguratorTest extends TestCase
{
    private function makeContext(object ...$attributes): FieldContext
    {
        return new FieldContext(
            new UntypedPropertyType(),
            $attributes,
            $this->createStub(FormTypeFactory::class),
            new ReflectionProperty(FieldConfig::class, 'name'),
        );
    }

    public function testDispatchesToMatchingConfigurator(): void
    {
        $mock = $this->createMock(TypedocConfigurator::class);
        $mock->method('typedoc')->willReturn(Typedocs\Name::class);
        $mock->expects(self::once())
            ->method('configure')
            ->with(self::isInstanceOf(FieldConfig::class), self::isInstanceOf(Typedocs\Name::class));

        $configurator = new TypedocsConfigurator([$mock]);
        $configurator->configure($this->makeContext(new Typedocs\Name('My Field')));
    }

    public function testSkipsNonTypedocAttributes(): void
    {
        $mock = $this->createMock(TypedocConfigurator::class);
        $mock->method('typedoc')->willReturn(Typedocs\Name::class);
        $mock->expects(self::never())->method('configure');

        $configurator = new TypedocsConfigurator([$mock]);
        // FieldName is a FieldAttribute, not a TypeDoc
        $configurator->configure($this->makeContext(new FieldName('foo')));
    }

    public function testReturnsEmptyConfigWhenNoMatch(): void
    {
        $configurator = new TypedocsConfigurator([]);
        $result = $configurator->configure($this->makeContext());

        self::assertNull($result->type);
        self::assertSame(['attr' => []], $result->options);
    }
}
