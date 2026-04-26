<?php

declare(strict_types=1);

namespace Fasano\FormsBundle\Tests\Unit\Configurator\BundleAttribute;

use Fasano\FormsBundle\Attribute\Field\Attributes;
use Fasano\FormsBundle\Configurator\BundleAttribute\AttributesConfigurator;
use Fasano\FormsBundle\Field\FieldConfig;
use Fasano\FormsBundle\Field\FieldContext;
use Fasano\FormsBundle\FormTypeFactory;
use PHPUnit\Framework\TestCase;
use ReflectionProperty;

class AttributesConfiguratorTest extends TestCase
{
    public string $stub;

    private AttributesConfigurator $configurator;

    protected function setUp(): void
    {
        $this->configurator = new AttributesConfigurator();
    }

    private function context(): FieldContext
    {
        $property = new ReflectionProperty(self::class, 'stub');
        return new FieldContext($property->getType(), [], $this->createStub(FormTypeFactory::class), $property);
    }

    public function testAttribute(): void
    {
        self::assertSame(Attributes::class, $this->configurator->attribute());
    }

    public function testConfigureMergesIntoAttr(): void
    {
        $config = new FieldConfig(options: ['attr' => ['class' => 'base']]);
        $this->configurator->configure($config, new Attributes(placeholder: 'Enter value'), $this->context());

        self::assertSame('base', $config->options['attr']['class']);
        self::assertSame('Enter value', $config->options['attr']['placeholder']);
    }

    public function testConfigureInitialisesAttrWhenMissing(): void
    {
        $config = new FieldConfig(options: []);
        $this->configurator->configure($config, new Attributes(id: 'my-field'), $this->context());

        self::assertSame('my-field', $config->options['attr']['id']);
    }
}
