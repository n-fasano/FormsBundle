<?php

namespace Fasano\FormsBundle\Tests\Unit\Configurator\Constraint;

use Fasano\FormsBundle\Configurator\Constraint\GreaterThanConfigurator;
use Fasano\FormsBundle\Field\FieldConfig;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Constraints\GreaterThan;

class GreaterThanConfiguratorTest extends TestCase
{
    private GreaterThanConfigurator $configurator;

    protected function setUp(): void
    {
        $this->configurator = new GreaterThanConfigurator();
    }

    public function testConstraint(): void
    {
        self::assertSame(GreaterThan::class, $this->configurator->constraint());
    }

    public function testConfigure(): void
    {
        $config = new FieldConfig();
        $constraint = new GreaterThan(10);

        $this->configurator->configure($config, $constraint);

        self::assertSame(11, $config->options['attr']['min']);
    }
}
