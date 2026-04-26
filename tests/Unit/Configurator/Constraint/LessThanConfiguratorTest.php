<?php

namespace Fasano\FormsBundle\Tests\Unit\Configurator\Constraint;

use Fasano\FormsBundle\Configurator\Constraint\LessThanConfigurator;
use Fasano\FormsBundle\Field\FieldConfig;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Constraints\LessThan;

class LessThanConfiguratorTest extends TestCase
{
    private LessThanConfigurator $configurator;

    protected function setUp(): void
    {
        $this->configurator = new LessThanConfigurator();
    }

    public function testConstraint(): void
    {
        self::assertSame(LessThan::class, $this->configurator->constraint());
    }

    public function testConfigure(): void
    {
        $config = new FieldConfig();
        $constraint = new LessThan(100);

        $this->configurator->configure($config, $constraint);

        self::assertSame(99, $config->options['attr']['max']);
    }
}
