<?php

namespace Fasano\FormsBundle\Tests\Unit\Configurator\Constraint;

use Fasano\FormsBundle\Configurator\Constraint\NotCompromisedPasswordConfigurator;
use Fasano\FormsBundle\Field\FieldConfig;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Validator\Constraints\NotCompromisedPassword;

class NotCompromisedPasswordConfiguratorTest extends TestCase
{
    private NotCompromisedPasswordConfigurator $configurator;

    protected function setUp(): void
    {
        $this->configurator = new NotCompromisedPasswordConfigurator();
    }

    public function testConstraint(): void
    {
        self::assertSame(NotCompromisedPassword::class, $this->configurator->constraint());
    }

    public function testConfigure(): void
    {
        $config = new FieldConfig();

        $this->configurator->configure($config, new NotCompromisedPassword());

        self::assertSame(PasswordType::class, $config->type);
    }
}
