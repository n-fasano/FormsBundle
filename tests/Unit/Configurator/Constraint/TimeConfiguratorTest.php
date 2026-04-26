<?php

namespace Fasano\FormsBundle\Tests\Unit\Configurator\Constraint;

use Fasano\FormsBundle\Configurator\Constraint\TimeConfigurator;
use Fasano\FormsBundle\Field\FieldConfig;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Validator\Constraints\Time;

class TimeConfiguratorTest extends TestCase
{
    private TimeConfigurator $configurator;

    protected function setUp(): void
    {
        $this->configurator = new TimeConfigurator();
    }

    public function testConstraint(): void
    {
        self::assertSame(Time::class, $this->configurator->constraint());
    }

    public function testConfigure(): void
    {
        $config = new FieldConfig();
        $constraint = new Time();

        $this->configurator->configure($config, $constraint);

        self::assertSame(TimeType::class, $config->type);
    }
}
