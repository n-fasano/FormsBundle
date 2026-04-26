<?php

namespace Fasano\FormsBundle\Tests\Unit\Configurator\Constraint;

use Fasano\FormsBundle\Configurator\Constraint\LocaleConfigurator;
use Fasano\FormsBundle\Field\FieldConfig;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Extension\Core\Type\LocaleType;
use Symfony\Component\Validator\Constraints\Locale;

class LocaleConfiguratorTest extends TestCase
{
    private LocaleConfigurator $configurator;

    protected function setUp(): void
    {
        $this->configurator = new LocaleConfigurator();
    }

    public function testConstraint(): void
    {
        self::assertSame(Locale::class, $this->configurator->constraint());
    }

    public function testConfigure(): void
    {
        $config = new FieldConfig();

        $this->configurator->configure($config, new Locale());

        self::assertSame(LocaleType::class, $config->type);
    }
}
