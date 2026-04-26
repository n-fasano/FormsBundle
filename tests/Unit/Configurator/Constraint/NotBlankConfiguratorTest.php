<?php

namespace Fasano\FormsBundle\Tests\Unit\Configurator\Constraint;

use Fasano\FormsBundle\Configurator\Constraint\NotBlankConfigurator;
use Fasano\FormsBundle\Field\FieldConfig;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Constraints\NotBlank;

class NotBlankConfiguratorTest extends TestCase
{
    private NotBlankConfigurator $configurator;

    protected function setUp(): void
    {
        $this->configurator = new NotBlankConfigurator();
    }

    public function testConstraint(): void
    {
        self::assertSame(NotBlank::class, $this->configurator->constraint());
    }

    public function testConfigure(): void
    {
        $config = new FieldConfig();
        $constraint = new NotBlank();

        $this->configurator->configure($config, $constraint);

        self::assertTrue($config->options['required']);
    }
}
