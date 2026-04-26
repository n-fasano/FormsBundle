<?php

namespace Fasano\FormsBundle\Tests\Unit\Configurator\Constraint;

use Fasano\FormsBundle\Configurator\Constraint\UuidConfigurator;
use Fasano\FormsBundle\Field\FieldConfig;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Constraints\Uuid;

class UuidConfiguratorTest extends TestCase
{
    private UuidConfigurator $configurator;

    protected function setUp(): void
    {
        $this->configurator = new UuidConfigurator();
    }

    public function testConstraint(): void
    {
        self::assertSame(Uuid::class, $this->configurator->constraint());
    }

    public function testConfigure(): void
    {
        $config = new FieldConfig();

        $this->configurator->configure($config, new Uuid());

        self::assertMatchesRegularExpression(
            '/^\[0-9a-fA-F\]/',
            $config->options['attr']['pattern'],
        );
    }
}
