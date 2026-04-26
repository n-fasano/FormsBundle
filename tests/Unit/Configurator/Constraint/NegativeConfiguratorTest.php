<?php

namespace Fasano\FormsBundle\Tests\Unit\Configurator\Constraint;

use Fasano\FormsBundle\Configurator\Constraint\NegativeConfigurator;
use Fasano\FormsBundle\Field\FieldConfig;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Constraints\Negative;

class NegativeConfiguratorTest extends TestCase
{
    private NegativeConfigurator $configurator;

    protected function setUp(): void
    {
        $this->configurator = new NegativeConfigurator();
    }

    public function testConstraint(): void
    {
        self::assertSame(Negative::class, $this->configurator->constraint());
    }

    public function testConfigure(): void
    {
        $config = new FieldConfig();
        $constraint = new Negative();

        $this->configurator->configure($config, $constraint);

        self::assertSame(-1, $config->options['attr']['max']);
    }
}
