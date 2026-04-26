<?php

namespace Fasano\FormsBundle\Tests\Unit\Configurator\Constraint;

use Fasano\FormsBundle\Configurator\Constraint\RegexConfigurator;
use Fasano\FormsBundle\Field\FieldConfig;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Constraints\Regex;

class RegexConfiguratorTest extends TestCase
{
    private RegexConfigurator $configurator;

    protected function setUp(): void
    {
        $this->configurator = new RegexConfigurator();
    }

    public function testConstraint(): void
    {
        self::assertSame(Regex::class, $this->configurator->constraint());
    }

    public function testConfigure(): void
    {
        $config = new FieldConfig();
        $constraint = new Regex('/^[a-z]+$/');

        $this->configurator->configure($config, $constraint);

        self::assertSame('[a-z]+', $config->options['attr']['pattern']);
    }
}
