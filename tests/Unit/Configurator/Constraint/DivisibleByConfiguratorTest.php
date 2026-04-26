<?php

namespace Fasano\FormsBundle\Tests\Unit\Configurator\Constraint;

use Fasano\FormsBundle\Configurator\Constraint\DivisibleByConfigurator;
use Fasano\FormsBundle\Field\FieldConfig;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Constraints\DivisibleBy;

class DivisibleByConfiguratorTest extends TestCase
{
    private DivisibleByConfigurator $configurator;

    protected function setUp(): void
    {
        $this->configurator = new DivisibleByConfigurator();
    }

    public function testConstraint(): void
    {
        self::assertSame(DivisibleBy::class, $this->configurator->constraint());
    }

    public function testConfigure(): void
    {
        $config = new FieldConfig();

        $this->configurator->configure($config, new DivisibleBy(5));

        self::assertSame(5, $config->options['attr']['step']);
    }
}
