<?php

namespace Fasano\FormsBundle\Tests\Unit\Configurator\Constraint;

use Fasano\FormsBundle\Configurator\Constraint\PositiveConfigurator;
use Fasano\FormsBundle\Field\FieldConfig;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Constraints\Positive;

class PositiveConfiguratorTest extends TestCase
{
    private PositiveConfigurator $configurator;

    protected function setUp(): void
    {
        $this->configurator = new PositiveConfigurator();
    }

    public function testConstraint(): void
    {
        self::assertSame(Positive::class, $this->configurator->constraint());
    }

    public function testConfigure(): void
    {
        $config = new FieldConfig();
        $constraint = new Positive();

        $this->configurator->configure($config, $constraint);

        self::assertSame(1, $config->options['attr']['min']);
    }
}
