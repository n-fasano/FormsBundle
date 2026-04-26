<?php

namespace Fasano\FormsBundle\Tests\Unit\Configurator\Constraint;

use Fasano\FormsBundle\Configurator\Constraint\CidrConfigurator;
use Fasano\FormsBundle\Field\FieldConfig;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Constraints\Cidr;
use Symfony\Component\Validator\Constraints\Ip;

class CidrConfiguratorTest extends TestCase
{
    private CidrConfigurator $configurator;

    protected function setUp(): void
    {
        $this->configurator = new CidrConfigurator();
    }

    public function testConstraint(): void
    {
        self::assertSame(Cidr::class, $this->configurator->constraint());
    }

    public function testConfigureV4MatchesIpv4Cidr(): void
    {
        $config = new FieldConfig();

        $this->configurator->configure($config, new Cidr(version: Ip::V4));

        self::assertMatchesRegularExpression('~^' . $config->options['attr']['pattern'] . '$~', '192.168.1.0/24');
    }

    public function testConfigureV6MatchesIpv6Cidr(): void
    {
        $config = new FieldConfig();

        $this->configurator->configure($config, new Cidr(version: Ip::V6));

        self::assertMatchesRegularExpression('~^' . $config->options['attr']['pattern'] . '$~', '2001:db8::/32');
    }

    public function testConfigureAllMatchesBoth(): void
    {
        $config = new FieldConfig();

        $this->configurator->configure($config, new Cidr(version: Ip::ALL));

        self::assertMatchesRegularExpression('~^(?:' . $config->options['attr']['pattern'] . ')$~', '192.168.1.0/24');
        self::assertMatchesRegularExpression('~^(?:' . $config->options['attr']['pattern'] . ')$~', '2001:db8::/32');
    }
}
