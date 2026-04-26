<?php

namespace Fasano\FormsBundle\Tests\Unit\Configurator\Constraint;

use Fasano\FormsBundle\Configurator\Constraint\LessThanOrEqualConfigurator;
use Fasano\FormsBundle\Field\FieldConfig;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;

class LessThanOrEqualConfiguratorTest extends TestCase
{
    private LessThanOrEqualConfigurator $configurator;

    protected function setUp(): void
    {
        $this->configurator = new LessThanOrEqualConfigurator();
    }

    public function testConstraint(): void
    {
        self::assertSame(LessThanOrEqual::class, $this->configurator->constraint());
    }

    public function testConfigure(): void
    {
        $config = new FieldConfig();
        $constraint = new LessThanOrEqual(100);

        $this->configurator->configure($config, $constraint);

        self::assertSame(100, $config->options['attr']['max']);
    }
}
