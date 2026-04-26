<?php

namespace Fasano\FormsBundle\Tests\Unit\Configurator\Constraint;

use Fasano\FormsBundle\Configurator\Constraint\DateConfigurator;
use Fasano\FormsBundle\Field\FieldConfig;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Validator\Constraints\Date;

class DateConfiguratorTest extends TestCase
{
    private DateConfigurator $configurator;

    protected function setUp(): void
    {
        $this->configurator = new DateConfigurator();
    }

    public function testConstraint(): void
    {
        self::assertSame(Date::class, $this->configurator->constraint());
    }

    public function testConfigure(): void
    {
        $config = new FieldConfig();
        $constraint = new Date();

        $this->configurator->configure($config, $constraint);

        self::assertSame(DateType::class, $config->type);
    }
}
