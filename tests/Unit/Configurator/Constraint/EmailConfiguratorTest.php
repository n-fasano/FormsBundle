<?php

namespace Fasano\FormsBundle\Tests\Unit\Configurator\Constraint;

use Fasano\FormsBundle\Configurator\Constraint\EmailConfigurator;
use Fasano\FormsBundle\Field\FieldConfig;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Validator\Constraints\Email;

class EmailConfiguratorTest extends TestCase
{
    private EmailConfigurator $configurator;

    protected function setUp(): void
    {
        $this->configurator = new EmailConfigurator();
    }

    public function testConstraint(): void
    {
        self::assertSame(Email::class, $this->configurator->constraint());
    }

    public function testConfigure(): void
    {
        $config = new FieldConfig();
        $constraint = new Email();

        $this->configurator->configure($config, $constraint);

        self::assertSame(EmailType::class, $config->type);
    }
}
