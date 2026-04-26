<?php

namespace Fasano\FormsBundle\Tests\Unit\Configurator\Constraint;

use Fasano\FormsBundle\Configurator\Constraint\PositiveOrZeroConfigurator;
use Fasano\FormsBundle\Field\FieldConfig;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Constraints\PositiveOrZero;

class PositiveOrZeroConfiguratorTest extends TestCase
{
    private PositiveOrZeroConfigurator $configurator;

    protected function setUp(): void
    {
        $this->configurator = new PositiveOrZeroConfigurator();
    }

    public function testConstraint(): void
    {
        self::assertSame(PositiveOrZero::class, $this->configurator->constraint());
    }

    public function testConfigure(): void
    {
        $config = new FieldConfig();
        $constraint = new PositiveOrZero();

        $this->configurator->configure($config, $constraint);

        self::assertSame(0, $config->options['attr']['min']);
    }
}
