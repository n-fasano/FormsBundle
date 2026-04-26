<?php

namespace Fasano\FormsBundle\Tests\Unit\Configurator\Constraint;

use Fasano\FormsBundle\Configurator\Constraint\GreaterThanOrEqualConfigurator;
use Fasano\FormsBundle\Field\FieldConfig;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;

class GreaterThanOrEqualConfiguratorTest extends TestCase
{
    private GreaterThanOrEqualConfigurator $configurator;

    protected function setUp(): void
    {
        $this->configurator = new GreaterThanOrEqualConfigurator();
    }

    public function testConstraint(): void
    {
        self::assertSame(GreaterThanOrEqual::class, $this->configurator->constraint());
    }

    public function testConfigure(): void
    {
        $config = new FieldConfig();
        $constraint = new GreaterThanOrEqual(10);

        $this->configurator->configure($config, $constraint);

        self::assertSame(10, $config->options['attr']['min']);
    }
}
