<?php

declare(strict_types=1);

namespace Fasano\FormsBundle\Tests\Unit\Configurator\BundleAttribute;

use Fasano\FormsBundle\Attribute\Field\EntryType;
use Fasano\FormsBundle\Configurator\BundleAttribute\EntryTypeConfigurator;
use Fasano\FormsBundle\Field\FieldConfig;
use Fasano\FormsBundle\Field\FieldContext;
use Fasano\FormsBundle\FormTypeFactory;
use PHPUnit\Framework\TestCase;
use ReflectionProperty;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class EntryTypeConfiguratorTest extends TestCase
{
    public string $stub;

    public function testAttribute(): void
    {
        self::assertSame(EntryType::class, (new EntryTypeConfigurator())->attribute());
    }

    public function testConfigureWithDtoClass(): void
    {
        $factory = $this->createMock(FormTypeFactory::class);
        $factory->expects(self::once())->method('createFormType')->with('App\SomeClass')->willReturn('Cache\Forms\SomeClassType');

        $property = new ReflectionProperty(self::class, 'stub');
        $context = new FieldContext($property->getType(), [], $factory, $property);

        $config = new FieldConfig();
        (new EntryTypeConfigurator())->configure($config, new EntryType('App\SomeClass'), $context);

        self::assertSame(CollectionType::class, $config->type);
        self::assertSame('Cache\Forms\SomeClassType', $config->options['entry_type']);
    }

    public function testConfigureWithFormTypeFqcn(): void
    {
        $formTypeFqcn = new class extends AbstractType {};

        $factory = $this->createMock(FormTypeFactory::class);
        $factory->expects(self::never())->method('createFormType');

        $property = new ReflectionProperty(self::class, 'stub');
        $context = new FieldContext($property->getType(), [], $factory, $property);

        $config = new FieldConfig();
        (new EntryTypeConfigurator())->configure($config, new EntryType($formTypeFqcn::class), $context);

        self::assertSame(CollectionType::class, $config->type);
        self::assertSame($formTypeFqcn::class, $config->options['entry_type']);
    }
}
