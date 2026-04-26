<?php

namespace Fasano\FormsBundle\Tests\Unit\Configurator\Constraint;

use Fasano\FormsBundle\Configurator\Constraint\IpConfigurator;
use Fasano\FormsBundle\Field\FieldConfig;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Constraints\Ip;

class IpConfiguratorTest extends TestCase
{
    private IpConfigurator $configurator;

    protected function setUp(): void
    {
        $this->configurator = new IpConfigurator();
    }

    public function testConstraint(): void
    {
        self::assertSame(Ip::class, $this->configurator->constraint());
    }

    public function testConfigureV4MatchesIpv4(): void
    {
        $config = new FieldConfig();

        $this->configurator->configure($config, new Ip(version: Ip::V4));

        self::assertMatchesRegularExpression('/^' . $config->options['attr']['pattern'] . '$/', '192.168.1.1');
        self::assertDoesNotMatchRegularExpression('/^' . $config->options['attr']['pattern'] . '$/', '2001:db8::1');
    }

    public function testConfigureV6MatchesIpv6(): void
    {
        $config = new FieldConfig();

        $this->configurator->configure($config, new Ip(version: Ip::V6));

        self::assertMatchesRegularExpression('/^' . $config->options['attr']['pattern'] . '$/', '2001:db8::1');
    }

    public function testConfigureAllMatchesBoth(): void
    {
        $config = new FieldConfig();

        $this->configurator->configure($config, new Ip(version: Ip::ALL));

        self::assertMatchesRegularExpression('/^(?:' . $config->options['attr']['pattern'] . ')$/', '192.168.1.1');
        self::assertMatchesRegularExpression('/^(?:' . $config->options['attr']['pattern'] . ')$/', '2001:db8::1');
    }
}
