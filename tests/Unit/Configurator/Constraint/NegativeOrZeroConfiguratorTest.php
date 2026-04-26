<?php

namespace Fasano\FormsBundle\Tests\Unit\Configurator\Constraint;

use Fasano\FormsBundle\Configurator\Constraint\NegativeOrZeroConfigurator;
use Fasano\FormsBundle\Field\FieldConfig;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Constraints\NegativeOrZero;

class NegativeOrZeroConfiguratorTest extends TestCase
{
    private NegativeOrZeroConfigurator $configurator;

    protected function setUp(): void
    {
        $this->configurator = new NegativeOrZeroConfigurator();
    }

    public function testConstraint(): void
    {
        self::assertSame(NegativeOrZero::class, $this->configurator->constraint());
    }

    public function testConfigure(): void
    {
        $config = new FieldConfig();
        $constraint = new NegativeOrZero();

        $this->configurator->configure($config, $constraint);

        self::assertSame(0, $config->options['attr']['max']);
    }
}
