<?php

namespace Fasano\FormsBundle\Tests\Unit\Configurator;

use Fasano\FormsBundle\Configurator\Constraint\ConstraintConfigurator;
use Fasano\FormsBundle\Configurator\ConstraintsConfigurator;
use Fasano\FormsBundle\Field\FieldConfig;
use Fasano\FormsBundle\Field\FieldContext;
use Fasano\FormsBundle\FormTypeFactory;
use Fasano\FormsBundle\Reflection\UntypedPropertyType;
use PHPUnit\Framework\TestCase;
use ReflectionProperty;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;

class ConstraintsConfiguratorTest extends TestCase
{
    private function makeContext(Constraint ...$constraints): FieldContext
    {
        return new FieldContext(
            new UntypedPropertyType(),
            $constraints,
            $this->createStub(FormTypeFactory::class),
            new ReflectionProperty(FieldConfig::class, 'name'),
        );
    }

    public function testConfigureWithMatchingConfigurator(): void
    {
        $emailConfiguratorMock = $this->createMock(ConstraintConfigurator::class);
        $emailConfiguratorMock->method('constraint')->willReturn(Email::class);
        $emailConfiguratorMock->expects(self::once())
            ->method('configure')
            ->with(self::isInstanceOf(FieldConfig::class), self::isInstanceOf(Email::class));

        $lengthConfiguratorMock = $this->createMock(ConstraintConfigurator::class);
        $lengthConfiguratorMock->method('constraint')->willReturn(Length::class);
        $lengthConfiguratorMock->expects(self::never())
            ->method('configure');

        $configurator = new ConstraintsConfigurator([
            $emailConfiguratorMock,
            $lengthConfiguratorMock,
        ]);

        $configurator->configure($this->makeContext(new Email()));
    }

    public function testConfigureWithNoMatchingConfigurator(): void
    {
        $emailConfiguratorMock = $this->createMock(ConstraintConfigurator::class);
        $emailConfiguratorMock->method('constraint')->willReturn(Email::class);
        $emailConfiguratorMock->expects(self::never())
            ->method('configure');

        $configurator = new ConstraintsConfigurator([$emailConfiguratorMock]);

        $result = $configurator->configure($this->makeContext(new Length(min: 5)));

        self::assertNull($result->type);
        self::assertSame(['attr' => []], $result->options);
    }

    public function testConfigureBuildsCorrectMap(): void
    {
        $configuratorMock1 = $this->createMock(ConstraintConfigurator::class);
        $configuratorMock1->method('constraint')->willReturn(Email::class);

        $configuratorMock2 = $this->createMock(ConstraintConfigurator::class);
        $configuratorMock2->method('constraint')->willReturn(Length::class);

        $configurator = new ConstraintsConfigurator([
            $configuratorMock1,
            $configuratorMock2,
        ]);

        $configuratorMock1->expects(self::once())->method('configure');
        $configurator->configure($this->makeContext(new Email()));

        $configuratorMock2->expects(self::once())->method('configure');
        $configurator->configure($this->makeContext(new Length(min: 5)));
    }
}
