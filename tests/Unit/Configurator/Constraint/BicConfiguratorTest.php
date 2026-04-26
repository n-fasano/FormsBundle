<?php

namespace Fasano\FormsBundle\Tests\Unit\Configurator\Constraint;

use Fasano\FormsBundle\Configurator\Constraint\BicConfigurator;
use Fasano\FormsBundle\Field\FieldConfig;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Constraints\Bic;

class BicConfiguratorTest extends TestCase
{
    private BicConfigurator $configurator;

    protected function setUp(): void
    {
        $this->configurator = new BicConfigurator();
    }

    public function testConstraint(): void
    {
        self::assertSame(Bic::class, $this->configurator->constraint());
    }

    public function testConfigure(): void
    {
        $config = new FieldConfig();

        $this->configurator->configure($config, new Bic());

        self::assertNotEmpty($config->options['attr']['pattern']);
    }

    public function testPatternMatchesValidBic8(): void
    {
        $config = new FieldConfig();

        $this->configurator->configure($config, new Bic());

        self::assertMatchesRegularExpression(
            '/^' . $config->options['attr']['pattern'] . '$/',
            'DEUTDEDB',
        );
    }

    public function testPatternMatchesValidBic11(): void
    {
        $config = new FieldConfig();

        $this->configurator->configure($config, new Bic());

        self::assertMatchesRegularExpression(
            '/^' . $config->options['attr']['pattern'] . '$/',
            'DEUTDEDBBER',
        );
    }

    public function testPatternRejectsInvalidBic(): void
    {
        $config = new FieldConfig();

        $this->configurator->configure($config, new Bic());

        self::assertDoesNotMatchRegularExpression(
            '/^' . $config->options['attr']['pattern'] . '$/',
            '12345678',
        );
    }
}
