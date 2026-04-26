<?php

namespace Fasano\FormsBundle\Tests\Unit\Configurator\Constraint;

use Fasano\FormsBundle\Configurator\Constraint\LengthConfigurator;
use Fasano\FormsBundle\Field\FieldConfig;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Constraints\Length;

class LengthConfiguratorTest extends TestCase
{
    private LengthConfigurator $configurator;

    protected function setUp(): void
    {
        $this->configurator = new LengthConfigurator();
    }

    public function testConstraint(): void
    {
        self::assertSame(Length::class, $this->configurator->constraint());
    }

    public function testConfigureWithMinAndMax(): void
    {
        $config = new FieldConfig();
        $constraint = new Length(min: 5, max: 20);

        $this->configurator->configure($config, $constraint);

        self::assertSame(5, $config->options['attr']['minlength']);
        self::assertSame(20, $config->options['attr']['maxlength']);
    }

    public function testConfigureWithMinOnly(): void
    {
        $config = new FieldConfig();
        $constraint = new Length(min: 3);

        $this->configurator->configure($config, $constraint);

        self::assertSame(3, $config->options['attr']['minlength']);
        self::assertArrayNotHasKey('maxlength', $config->options['attr']);
    }

    public function testConfigureWithMaxOnly(): void
    {
        $config = new FieldConfig();
        $constraint = new Length(max: 50);

        $this->configurator->configure($config, $constraint);

        self::assertArrayNotHasKey('minlength', $config->options['attr']);
        self::assertSame(50, $config->options['attr']['maxlength']);
    }
}
