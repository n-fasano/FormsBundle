<?php

declare(strict_types=1);

namespace Fasano\FormsBundle\Tests\Unit\Configurator;

use DateTime;
use DateTimeImmutable;
use Fasano\FormsBundle\Configurator\TypesystemConfigurator;
use Fasano\FormsBundle\Field\FieldConfig;
use Fasano\FormsBundle\Field\FieldContext;
use Fasano\FormsBundle\FormTypeFactory;
use ReflectionProperty;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

enum TypesystemTestEnum: string { case A = 'a'; }

class TypesystemTestHolder
{
    public string $stringProp;
    public int $intProp;
    public float $floatProp;
    public bool $boolProp;
    public array $arrayProp;
    public ?string $nullableString;
    public DateTime $dateTime;
    public DateTimeImmutable $dateTimeImmutable;
    public TypesystemTestEnum $enum;
}

class TypesystemConfiguratorTest extends TestCase
{
    private TypesystemConfigurator $configurator;

    protected function setUp(): void
    {
        $this->configurator = new TypesystemConfigurator();
    }

    private function configure(string $property): FieldConfig
    {
        $factory = $this->createStub(FormTypeFactory::class);
        $prop = new ReflectionProperty(TypesystemTestHolder::class, $property);
        $context = new FieldContext($prop->getType(), [], $factory, $prop);

        return $this->configurator->configure($context);
    }

    public function testString(): void
    {
        $config = $this->configure('stringProp');

        self::assertSame(TextType::class, $config->type);
        self::assertTrue($config->options['required']);
        self::assertSame('String Prop', $config->options['label']);
    }

    public function testInt(): void
    {
        self::assertSame(IntegerType::class, $this->configure('intProp')->type);
    }

    public function testFloat(): void
    {
        self::assertSame(NumberType::class, $this->configure('floatProp')->type);
    }

    public function testBool(): void
    {
        self::assertSame(CheckboxType::class, $this->configure('boolProp')->type);
    }

    public function testArray(): void
    {
        self::assertSame(CollectionType::class, $this->configure('arrayProp')->type);
    }

    public function testNullableMeansNotRequired(): void
    {
        $config = $this->configure('nullableString');

        self::assertFalse($config->options['required']);
    }

    public function testDateTime(): void
    {
        $config = $this->configure('dateTime');

        self::assertSame(DateTimeType::class, $config->type);
        self::assertSame('datetime', $config->options['input']);
    }

    public function testDateTimeImmutable(): void
    {
        $config = $this->configure('dateTimeImmutable');

        self::assertSame(DateTimeType::class, $config->type);
        self::assertSame('datetime_immutable', $config->options['input']);
    }

    public function testEnum(): void
    {
        $config = $this->configure('enum');

        self::assertSame(EnumType::class, $config->type);
        self::assertSame(TypesystemTestEnum::class, $config->options['class']);
    }
}
