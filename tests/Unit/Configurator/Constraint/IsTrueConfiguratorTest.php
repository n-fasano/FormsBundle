<?php

namespace Fasano\FormsBundle\Tests\Unit\Configurator\Constraint;

use Fasano\FormsBundle\Configurator\Constraint\IsTrueConfigurator;
use Fasano\FormsBundle\Field\FieldConfig;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Validator\Constraints\IsTrue;

class IsTrueConfiguratorTest extends TestCase
{
    private IsTrueConfigurator $configurator;

    protected function setUp(): void
    {
        $this->configurator = new IsTrueConfigurator();
    }

    public function testConstraint(): void
    {
        self::assertSame(IsTrue::class, $this->configurator->constraint());
    }

    public function testConfigure(): void
    {
        $config = new FieldConfig();

        $this->configurator->configure($config, new IsTrue());

        self::assertSame(CheckboxType::class, $config->type);
    }
}
