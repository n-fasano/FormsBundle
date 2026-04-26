<?php

declare(strict_types=1);

namespace Fasano\FormsBundle\Tests\Unit\Configurator\BundleAttribute;

use Fasano\FormsBundle\Attribute\Field\Name;
use Fasano\FormsBundle\Configurator\BundleAttribute\NameConfigurator;
use Fasano\FormsBundle\Field\FieldConfig;
use Fasano\FormsBundle\Field\FieldContext;
use Fasano\FormsBundle\FormTypeFactory;
use PHPUnit\Framework\TestCase;
use ReflectionProperty;

class NameConfiguratorTest extends TestCase
{
    public string $stub;

    private NameConfigurator $configurator;

    protected function setUp(): void
    {
        $this->configurator = new NameConfigurator();
    }

    private function context(): FieldContext
    {
        $property = new ReflectionProperty(self::class, 'stub');
        return new FieldContext($property->getType(), [], $this->createStub(FormTypeFactory::class), $property);
    }

    public function testAttribute(): void
    {
        self::assertSame(Name::class, $this->configurator->attribute());
    }

    public function testConfigure(): void
    {
        $config = new FieldConfig();
        $this->configurator->configure($config, new Name('myField'), $this->context());

        self::assertSame('myField', $config->name);
    }
}
