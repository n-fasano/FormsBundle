<?php

namespace Fasano\FormsBundle\Tests\Unit\Configurator\Constraint;

use Fasano\FormsBundle\Configurator\Constraint\CurrencyConfigurator;
use Fasano\FormsBundle\Field\FieldConfig;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Extension\Core\Type\CurrencyType;
use Symfony\Component\Validator\Constraints\Currency;

class CurrencyConfiguratorTest extends TestCase
{
    private CurrencyConfigurator $configurator;

    protected function setUp(): void
    {
        $this->configurator = new CurrencyConfigurator();
    }

    public function testConstraint(): void
    {
        self::assertSame(Currency::class, $this->configurator->constraint());
    }

    public function testConfigure(): void
    {
        $config = new FieldConfig();

        $this->configurator->configure($config, new Currency());

        self::assertSame(CurrencyType::class, $config->type);
    }
}
