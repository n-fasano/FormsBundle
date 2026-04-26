<?php

namespace Fasano\FormsBundle\Tests\Unit\Configurator\Constraint;

use Fasano\FormsBundle\Configurator\Constraint\DateTimeConfigurator;
use Fasano\FormsBundle\Field\FieldConfig;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Validator\Constraints\DateTime;

class DateTimeConfiguratorTest extends TestCase
{
    private DateTimeConfigurator $configurator;

    protected function setUp(): void
    {
        $this->configurator = new DateTimeConfigurator();
    }

    public function testConstraint(): void
    {
        self::assertSame(DateTime::class, $this->configurator->constraint());
    }

    public function testConfigure(): void
    {
        $config = new FieldConfig();
        $constraint = new DateTime();

        $this->configurator->configure($config, $constraint);

        self::assertSame(DateTimeType::class, $config->type);
    }
}
