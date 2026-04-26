<?php

declare(strict_types=1);

namespace Fasano\FormsBundle\Tests\Unit\Configurator\BundleAttribute;

use Fasano\FormsBundle\Attribute\Field\Type;
use Fasano\FormsBundle\Configurator\BundleAttribute\TypeConfigurator;
use Fasano\FormsBundle\Field\FieldConfig;
use Fasano\FormsBundle\Field\FieldContext;
use Fasano\FormsBundle\FormTypeFactory;
use PHPUnit\Framework\TestCase;
use ReflectionProperty;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class TypeConfiguratorTest extends TestCase
{
    public string $stub;

    private TypeConfigurator $configurator;

    protected function setUp(): void
    {
        $this->configurator = new TypeConfigurator();
    }

    private function context(): FieldContext
    {
        $property = new ReflectionProperty(self::class, 'stub');
        return new FieldContext($property->getType(), [], $this->createStub(FormTypeFactory::class), $property);
    }

    public function testAttribute(): void
    {
        self::assertSame(Type::class, $this->configurator->attribute());
    }

    public function testConfigure(): void
    {
        $config = new FieldConfig();
        $this->configurator->configure($config, new Type(TextareaType::class), $this->context());

        self::assertSame(TextareaType::class, $config->type);
    }
}
