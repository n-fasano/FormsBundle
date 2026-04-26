<?php

declare(strict_types=1);

namespace Fasano\FormsBundle\Tests\Unit\Configurator\BundleAttribute;

use Fasano\FormsBundle\Attribute\Field\Options;
use Fasano\FormsBundle\Configurator\BundleAttribute\OptionsConfigurator;
use Fasano\FormsBundle\Field\FieldConfig;
use Fasano\FormsBundle\Field\FieldContext;
use Fasano\FormsBundle\FormTypeFactory;
use PHPUnit\Framework\TestCase;
use ReflectionProperty;

class OptionsConfiguratorTest extends TestCase
{
    public string $stub;

    private OptionsConfigurator $configurator;

    protected function setUp(): void
    {
        $this->configurator = new OptionsConfigurator();
    }

    private function context(): FieldContext
    {
        $property = new ReflectionProperty(self::class, 'stub');
        return new FieldContext($property->getType(), [], $this->createStub(FormTypeFactory::class), $property);
    }

    public function testAttribute(): void
    {
        self::assertSame(Options::class, $this->configurator->attribute());
    }

    public function testConfigure(): void
    {
        $config = new FieldConfig(options: ['required' => true, 'attr' => []]);
        $this->configurator->configure($config, new Options(label: 'My Label', help: 'Hint'), $this->context());

        self::assertSame('My Label', $config->options['label']);
        self::assertSame('Hint', $config->options['help']);
        self::assertTrue($config->options['required']);
    }
}
