<?php

namespace Fasano\FormsBundle\Tests\Unit\Configurator\Constraint;

use Fasano\FormsBundle\Configurator\Constraint\IbanConfigurator;
use Fasano\FormsBundle\Field\FieldConfig;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Constraints\Iban;

class IbanConfiguratorTest extends TestCase
{
    private IbanConfigurator $configurator;

    protected function setUp(): void
    {
        $this->configurator = new IbanConfigurator();
    }

    public function testConstraint(): void
    {
        self::assertSame(Iban::class, $this->configurator->constraint());
    }

    public function testConfigure(): void
    {
        $config = new FieldConfig();

        $this->configurator->configure($config, new Iban());

        self::assertNotEmpty($config->options['attr']['pattern']);
    }

    public function testPatternMatchesValidIban(): void
    {
        $config = new FieldConfig();

        $this->configurator->configure($config, new Iban());

        self::assertMatchesRegularExpression(
            '/^' . $config->options['attr']['pattern'] . '$/',
            'GB29NWBK60161331926819',
        );
    }

    public function testPatternRejectsInvalidIban(): void
    {
        $config = new FieldConfig();

        $this->configurator->configure($config, new Iban());

        self::assertDoesNotMatchRegularExpression(
            '/^' . $config->options['attr']['pattern'] . '$/',
            '12345',
        );
    }
}
