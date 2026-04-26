<?php

namespace Fasano\FormsBundle\Tests\Unit\Configurator\Constraint;

use Fasano\FormsBundle\Configurator\Constraint\MacAddressConfigurator;
use Fasano\FormsBundle\Field\FieldConfig;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Constraints\MacAddress;

class MacAddressConfiguratorTest extends TestCase
{
    private MacAddressConfigurator $configurator;

    protected function setUp(): void
    {
        $this->configurator = new MacAddressConfigurator();
    }

    public function testConstraint(): void
    {
        self::assertSame(MacAddress::class, $this->configurator->constraint());
    }

    public function testPatternMatchesColonSeparated(): void
    {
        $config = new FieldConfig();

        $this->configurator->configure($config, new MacAddress());

        self::assertMatchesRegularExpression('/^' . $config->options['attr']['pattern'] . '$/', '00:1A:2B:3C:4D:5E');
    }

    public function testPatternMatchesDashSeparated(): void
    {
        $config = new FieldConfig();

        $this->configurator->configure($config, new MacAddress());

        self::assertMatchesRegularExpression('/^' . $config->options['attr']['pattern'] . '$/', '00-1A-2B-3C-4D-5E');
    }

    public function testPatternRejectsInvalidAddress(): void
    {
        $config = new FieldConfig();

        $this->configurator->configure($config, new MacAddress());

        self::assertDoesNotMatchRegularExpression('/^' . $config->options['attr']['pattern'] . '$/', '00:1A:2B:3C:4D');
    }
}
