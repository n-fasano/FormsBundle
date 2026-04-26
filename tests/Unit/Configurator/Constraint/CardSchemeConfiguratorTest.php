<?php

namespace Fasano\FormsBundle\Tests\Unit\Configurator\Constraint;

use Fasano\FormsBundle\Configurator\Constraint\CardSchemeConfigurator;
use Fasano\FormsBundle\Field\FieldConfig;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Constraints\CardScheme;

class CardSchemeConfiguratorTest extends TestCase
{
    private CardSchemeConfigurator $configurator;

    protected function setUp(): void
    {
        $this->configurator = new CardSchemeConfigurator();
    }

    public function testConstraint(): void
    {
        self::assertSame(CardScheme::class, $this->configurator->constraint());
    }

    public function testConfigureVisaMatchesVisaCard(): void
    {
        $config = new FieldConfig();

        $this->configurator->configure($config, new CardScheme(schemes: ['VISA']));

        self::assertMatchesRegularExpression('/^(?:' . $config->options['attr']['pattern'] . ')$/', '4111111111111111');
    }

    public function testConfigureVisaRejectsMastercard(): void
    {
        $config = new FieldConfig();

        $this->configurator->configure($config, new CardScheme(schemes: ['VISA']));

        self::assertDoesNotMatchRegularExpression('/^(?:' . $config->options['attr']['pattern'] . ')$/', '5500005555555559');
    }

    public function testConfigureMultipleSchemesMatchesEither(): void
    {
        $config = new FieldConfig();

        $this->configurator->configure($config, new CardScheme(schemes: ['VISA', 'MASTERCARD']));

        self::assertMatchesRegularExpression('/^(?:' . $config->options['attr']['pattern'] . ')$/', '4111111111111111');
        self::assertMatchesRegularExpression('/^(?:' . $config->options['attr']['pattern'] . ')$/', '5500005555555559');
    }

    public function testConfigureUnknownSchemesFallsBackToGenericPattern(): void
    {
        $config = new FieldConfig();

        $this->configurator->configure($config, new CardScheme(schemes: ['UNKNOWN_SCHEME']));

        self::assertMatchesRegularExpression('/^' . $config->options['attr']['pattern'] . '$/', '4111111111111111');
    }
}
