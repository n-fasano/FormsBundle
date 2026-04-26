<?php

namespace Fasano\FormsBundle\Tests\Unit\Configurator\Constraint;

use Fasano\FormsBundle\Configurator\Constraint\PasswordStrengthConfigurator;
use Fasano\FormsBundle\Field\FieldConfig;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Validator\Constraints\PasswordStrength;

class PasswordStrengthConfiguratorTest extends TestCase
{
    private PasswordStrengthConfigurator $configurator;

    protected function setUp(): void
    {
        $this->configurator = new PasswordStrengthConfigurator();
    }

    public function testConstraint(): void
    {
        self::assertSame(PasswordStrength::class, $this->configurator->constraint());
    }

    public function testConfigure(): void
    {
        $config = new FieldConfig();

        $this->configurator->configure($config, new PasswordStrength());

        self::assertSame(PasswordType::class, $config->type);
    }
}
