<?php

declare(strict_types=1);

namespace Fasano\FormsBundle\Field;

use Fasano\FormsBundle\FormTypeFactory;
use Fasano\FormsBundle\Reflection\CustomPropertyType;
use Fasano\FormsBundle\Reflection\UntypedPropertyType;
use Fasano\FormsBundle\Template\PrimitiveTransformerTemplate;
use Fasano\PHPrimitives\Contract\Primitive;
use Fasano\PHPrimitives\Contract\Primitive\BooleanPrimitive;
use Fasano\PHPrimitives\Contract\Primitive\FloatPrimitive;
use Fasano\PHPrimitives\Contract\Primitive\IntegerPrimitive;
use Fasano\PHPrimitives\Contract\Primitive\StringPrimitive;
use LogicException;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionIntersectionType;
use ReflectionProperty;
use ReflectionUnionType;

class FieldAnalyzer
{
    /**
     * @param FieldConfigurator[] $configurators Ordered: typesystem < constraints < typedocs < bundle
     */
    public function __construct(
        protected array $configurators,
        protected FormTypeFactory $factory,
    ) {}

    public function analyze(ReflectionProperty $property): FieldDefinition
    {
        $propertyType = $property->getType() ?? new UntypedPropertyType;

        if ($propertyType instanceof ReflectionUnionType || $propertyType instanceof ReflectionIntersectionType) {
            throw new LogicException('Union and intersection types are not supported in dynamic forms. Property: ' . $property->getDeclaringClass()->getShortName() . '::' . $property->getName());
        }

        $attributes = array_map(
            fn (ReflectionAttribute $attribute): object => $attribute->newInstance(),
            $property->getAttributes(),
        );
        $transformer = null;

        // 1. Primitive check: override type by its scalar backing, merge attributes, define transformer
        if (!$propertyType->isBuiltin()) {
            $classReflection = new ReflectionClass($propertyType->getName());

            if ($classReflection->implementsInterface(Primitive::class)) {
                $primitiveAttributes = array_map(
                    fn (ReflectionAttribute $attribute): object => $attribute->newInstance(),
                    $classReflection->getAttributes(),
                );
                $attributes = [...$attributes, ...$primitiveAttributes];
                $transformer = new PrimitiveTransformerTemplate('\\' . $propertyType->getName())->render();

                $propertyType = match (true) {
                    $classReflection->implementsInterface(StringPrimitive::class)  => new CustomPropertyType('string'),
                    $classReflection->implementsInterface(IntegerPrimitive::class) => new CustomPropertyType('int'),
                    $classReflection->implementsInterface(FloatPrimitive::class)   => new CustomPropertyType('float'),
                    $classReflection->implementsInterface(BooleanPrimitive::class) => new CustomPropertyType('bool'),
                    default => new UntypedPropertyType(),
                };
            }
        }

        $context = new FieldContext($propertyType, $attributes, $this->factory, $property);

        // 2 & 3. Run configurators and merge: later configurators have higher priority
        $merged = array_reduce(
            $this->configurators,
            fn (FieldConfig $carry, FieldConfigurator $configurator): FieldConfig
                => $carry->override($configurator->configure($context)),
            new FieldConfig(),
        );

        return new FieldDefinition($merged->name, $merged->type, $merged->options, $transformer);
    }
}