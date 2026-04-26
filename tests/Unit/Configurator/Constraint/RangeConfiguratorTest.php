<?php

namespace Fasano\FormsBundle\Tests\Unit\Configurator\Constraint;

use Fasano\FormsBundle\Configurator\Constraint\RangeConfigurator;
use Fasano\FormsBundle\Field\FieldConfig;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Constraints\Range;

class RangeConfiguratorTest extends TestCase
{
    private RangeConfigurator $configurator;

    protected function setUp(): void
    {
        $this->configurator = new RangeConfigurator();
    }

    public function testConstraint(): void
    {
        self::assertSame(Range::class, $this->configurator->constraint());
    }

    public function testConfigure(): void
    {
        $config = new FieldConfig();
        $constraint = new Range(min: 10, max: 100);

        $this->configurator->configure($config, $constraint);

        self::assertSame(10, $config->options['attr']['min']);
        self::assertSame(100, $config->options['attr']['max']);
    }
}
