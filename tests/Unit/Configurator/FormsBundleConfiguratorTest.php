<?php

declare(strict_types=1);

namespace Fasano\FormsBundle\Tests\Unit\Configurator;

use Fasano\FormsBundle\Attribute\Field\Name;
use Fasano\FormsBundle\Attribute\Field\Type;
use Fasano\FormsBundle\Configurator\BundleAttribute\BundleAttributeConfigurator;
use Fasano\FormsBundle\Configurator\FormsBundleConfigurator;
use Fasano\FormsBundle\Field\FieldConfig;
use Fasano\FormsBundle\Field\FieldContext;
use Fasano\FormsBundle\FormTypeFactory;
use Fasano\FormsBundle\Reflection\UntypedPropertyType;
use PHPUnit\Framework\TestCase;
use ReflectionProperty;
use Symfony\Component\Validator\Constraints\Email;

class FormsBundleConfiguratorTest extends TestCase
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
        $mock = $this->createMock(BundleAttributeConfigurator::class);
        $mock->method('attribute')->willReturn(Name::class);
        $mock->expects(self::once())
            ->method('configure')
            ->with(self::isInstanceOf(FieldConfig::class), self::isInstanceOf(Name::class));

        $configurator = new FormsBundleConfigurator([$mock]);
        $configurator->configure($this->makeContext(new Name('foo')));
    }

    public function testSkipsNonFieldAttributes(): void
    {
        $mock = $this->createMock(BundleAttributeConfigurator::class);
        $mock->method('attribute')->willReturn(Name::class);
        $mock->expects(self::never())->method('configure');

        $configurator = new FormsBundleConfigurator([$mock]);
        // Email is a Constraint, not a FieldAttribute
        $configurator->configure($this->makeContext(new Email()));
    }

    public function testSkipsUnregisteredAttributes(): void
    {
        $mock = $this->createMock(BundleAttributeConfigurator::class);
        $mock->method('attribute')->willReturn(Name::class);
        $mock->expects(self::never())->method('configure');

        $configurator = new FormsBundleConfigurator([$mock]);
        $configurator->configure($this->makeContext(new Type('SomeType')));
    }

    public function testReturnsEmptyConfigWhenNoMatch(): void
    {
        $configurator = new FormsBundleConfigurator([]);
        $result = $configurator->configure($this->makeContext());

        self::assertNull($result->type);
        self::assertSame(['attr' => []], $result->options);
    }
}
