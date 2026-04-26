<?php

namespace Fasano\FormsBundle\Tests\Unit\Configurator\Constraint;

use Fasano\FormsBundle\Configurator\Constraint\NotNullConfigurator;
use Fasano\FormsBundle\Field\FieldConfig;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Constraints\NotNull;

class NotNullConfiguratorTest extends TestCase
{
    private NotNullConfigurator $configurator;

    protected function setUp(): void
    {
        $this->configurator = new NotNullConfigurator();
    }

    public function testConstraint(): void
    {
        self::assertSame(NotNull::class, $this->configurator->constraint());
    }

    public function testConfigure(): void
    {
        $config = new FieldConfig();

        $this->configurator->configure($config, new NotNull());

        self::assertTrue($config->options['required']);
    }
}
