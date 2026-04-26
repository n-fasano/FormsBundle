<?php

namespace Fasano\FormsBundle\Tests\Unit\Configurator\Constraint;

use Fasano\FormsBundle\Configurator\Constraint\HostnameConfigurator;
use Fasano\FormsBundle\Field\FieldConfig;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Constraints\Hostname;

class HostnameConfiguratorTest extends TestCase
{
    private HostnameConfigurator $configurator;

    protected function setUp(): void
    {
        $this->configurator = new HostnameConfigurator();
    }

    public function testConstraint(): void
    {
        self::assertSame(Hostname::class, $this->configurator->constraint());
    }

    public function testConfigureWithTldRequiredMatchesFqdn(): void
    {
        $config = new FieldConfig();

        $this->configurator->configure($config, new Hostname(requireTld: true));

        self::assertMatchesRegularExpression('/^' . $config->options['attr']['pattern'] . '$/', 'example.com');
        self::assertMatchesRegularExpression('/^' . $config->options['attr']['pattern'] . '$/', 'sub.example.co.uk');
    }

    public function testConfigureWithTldRequiredRejectsBareName(): void
    {
        $config = new FieldConfig();

        $this->configurator->configure($config, new Hostname(requireTld: true));

        self::assertDoesNotMatchRegularExpression('/^' . $config->options['attr']['pattern'] . '$/', 'localhost');
    }

    public function testConfigureWithoutTldAcceptsBareName(): void
    {
        $config = new FieldConfig();

        $this->configurator->configure($config, new Hostname(requireTld: false));

        self::assertMatchesRegularExpression('/^' . $config->options['attr']['pattern'] . '$/', 'localhost');
        self::assertMatchesRegularExpression('/^' . $config->options['attr']['pattern'] . '$/', 'example.com');
    }
}
