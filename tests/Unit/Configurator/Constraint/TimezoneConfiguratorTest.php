<?php

namespace Fasano\FormsBundle\Tests\Unit\Configurator\Constraint;

use Fasano\FormsBundle\Configurator\Constraint\TimezoneConfigurator;
use Fasano\FormsBundle\Field\FieldConfig;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Extension\Core\Type\TimezoneType;
use Symfony\Component\Validator\Constraints\Timezone;

class TimezoneConfiguratorTest extends TestCase
{
    private TimezoneConfigurator $configurator;

    protected function setUp(): void
    {
        $this->configurator = new TimezoneConfigurator();
    }

    public function testConstraint(): void
    {
        self::assertSame(Timezone::class, $this->configurator->constraint());
    }

    public function testConfigure(): void
    {
        $config = new FieldConfig();

        $this->configurator->configure($config, new Timezone());

        self::assertSame(TimezoneType::class, $config->type);
    }
}
