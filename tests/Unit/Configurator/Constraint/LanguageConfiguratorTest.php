<?php

namespace Fasano\FormsBundle\Tests\Unit\Configurator\Constraint;

use Fasano\FormsBundle\Configurator\Constraint\LanguageConfigurator;
use Fasano\FormsBundle\Field\FieldConfig;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Extension\Core\Type\LanguageType;
use Symfony\Component\Validator\Constraints\Language;

class LanguageConfiguratorTest extends TestCase
{
    private LanguageConfigurator $configurator;

    protected function setUp(): void
    {
        $this->configurator = new LanguageConfigurator();
    }

    public function testConstraint(): void
    {
        self::assertSame(Language::class, $this->configurator->constraint());
    }

    public function testConfigure(): void
    {
        $config = new FieldConfig();

        $this->configurator->configure($config, new Language());

        self::assertSame(LanguageType::class, $config->type);
    }
}
