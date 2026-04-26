<?php

namespace Fasano\FormsBundle\Tests\Unit\Configurator\Constraint;

use Fasano\FormsBundle\Configurator\Constraint\UrlConfigurator;
use Fasano\FormsBundle\Field\FieldConfig;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Validator\Constraints\Url;

class UrlConfiguratorTest extends TestCase
{
    private UrlConfigurator $configurator;

    protected function setUp(): void
    {
        $this->configurator = new UrlConfigurator();
    }

    public function testConstraint(): void
    {
        self::assertSame(Url::class, $this->configurator->constraint());
    }

    public function testConfigure(): void
    {
        $config = new FieldConfig();
        $constraint = new Url();

        $this->configurator->configure($config, $constraint);

        self::assertSame(UrlType::class, $config->type);
    }
}
