<?php

namespace Fasano\FormsBundle\Tests\Unit\Configurator\Constraint;

use Fasano\FormsBundle\Configurator\Constraint\ChoiceConfigurator;
use Fasano\FormsBundle\Field\FieldConfig;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints\Choice;

class ChoiceConfiguratorTest extends TestCase
{
    private ChoiceConfigurator $configurator;

    protected function setUp(): void
    {
        $this->configurator = new ChoiceConfigurator();
    }

    public function testConstraint(): void
    {
        self::assertSame(Choice::class, $this->configurator->constraint());
    }

    public function testConfigure(): void
    {
        $config = new FieldConfig();

        $this->configurator->configure($config, new Choice(choices: ['a', 'b', 'c']));

        self::assertSame(ChoiceType::class, $config->type);
        self::assertSame(['a' => 'a', 'b' => 'b', 'c' => 'c'], $config->options['choices']);
    }

    public function testConfigureWithoutChoicesDoesNotSetChoicesOption(): void
    {
        $config = new FieldConfig();

        $this->configurator->configure($config, new Choice(callback: fn () => ['a', 'b']));

        self::assertArrayNotHasKey('choices', $config->options);
    }

    public function testConfigureWithMultiple(): void
    {
        $config = new FieldConfig();

        $this->configurator->configure($config, new Choice(choices: ['a', 'b'], multiple: true));

        self::assertTrue($config->options['multiple']);
    }

    public function testConfigureWithoutMultipleDoesNotSetMultipleOption(): void
    {
        $config = new FieldConfig();

        $this->configurator->configure($config, new Choice(choices: ['a', 'b']));

        self::assertArrayNotHasKey('multiple', $config->options);
    }
}
