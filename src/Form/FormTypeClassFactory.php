<?php

namespace Fasano\FormsBundle\Form;

use Contract\Primitives\Primitive\ScalarPrimitive;
use Fasano\DomainMetadata\DomainAttribute;
use Fasano\DomainMetadata\Attribute as Metadata;
use Fasano\FormsBundle\Attribute\DynamicFormAttribute;
use Fasano\FormsBundle\Attribute\Field\Attributes;
use Fasano\FormsBundle\Attribute\Field\Name;
use Fasano\FormsBundle\Attribute\Field\OptGroup;
use Fasano\FormsBundle\Attribute\Field\Options;
use Fasano\FormsBundle\Attribute\Field\Type;
use Fasano\FormsBundle\Attribute\Form\Button;
use Fasano\FormsBundle\Reflection\UntypedPropertyType;
use Doctrine\ORM\Mapping\Entity;
use LogicException;
use ReflectionClass;
use ReflectionIntersectionType;
use ReflectionNamedType;
use ReflectionProperty;
use ReflectionUnionType;
use Fasano\FormsBundle\Toolbox\ArrayMerger;
use Fasano\FormsBundle\Toolbox\ReflectionUtils;
use Fasano\FormsBundle\Toolbox\StringUtils;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\LessThan;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Constraints\Negative;
use Symfony\Component\Validator\Constraints\NegativeOrZero;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\Validator\Constraints\PositiveOrZero;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Time;
use Symfony\Component\Validator\Constraints\Url;
use Symfony\Component\Validator\Constraints\Valid;

class FormTypeClassFactory
{
    protected const string NAMESPACE = 'Cache\\FormType';
    protected const string CACHE_SUBDIR = 'formtypebundle/';

    public function __construct(
        #[Autowire(service: 'service_container')]
        private ContainerInterface $container,

        private UrlGeneratorInterface $urlGenerator,
        private Filesystem $filesystem,
        private string $cacheDir,
        private bool $enableCache,
    ) {}

    public function create(string $fqcn): FormTypeMetadata
    {
        $metadata = $this->getFormTypeMetadata($fqcn);

        if (!$this->enableCache || !$this->filesystem->exists($metadata->path)) {
            $sourceCode = $this->doCreate($fqcn);

            $this->filesystem->dumpFile($metadata->path, $sourceCode);
        }

        require_once $metadata->path;

        return $metadata;
    }

    protected function getFormTypeMetadata(string $fqcn): FormTypeMetadata
    {
        $reflection = new ReflectionClass($fqcn);
        $formTypeShortName = str_replace('\\', '_', $reflection->getName()) . 'FormType';
        
        return new FormTypeMetadata(
            FormTypeClassFactory::NAMESPACE . '\\' . $formTypeShortName,
            $this->cacheDir . self::CACHE_SUBDIR . $formTypeShortName . '.php',
        );
    }

    protected function doCreate(string $fqcn): string
    {
        # @TODO: Also don't forget to enrich the Form itself, can use Name, Options & Attributes attributes.
        # @TODO: Some more constraint options can be derived from ValidatorTypeGuesser 

        $reflection = new ReflectionClass($fqcn);

        $namespace = self::NAMESPACE;
        $className = str_replace('\\', '_', $reflection->getName()) . 'FormType';

        $formOptions = [];
        if ($form = ReflectionUtils::findAttribute($reflection, Options::class)) {
            $formOptions = $form->value;

            if (isset($formOptions['action'])) {
                $formOptions['action'] = $this->urlGenerator->generate($formOptions['action']);
            }
        }
        $formOptions['data_class'] = $fqcn;
        $formOptions['constraints'] = array_merge($formOptions['constraints'] ?? [], ['<valid>']);
        $formOptions = var_export($formOptions, true);
        $formOptions = strtr($formOptions, ['\'<valid>\'' => 'new \\'. Valid::class . '()']);

        $adds = '';

        foreach ($this->extractFormFields($reflection) as $field) {
            $fieldOptions = var_export($field->options, true);
            $builderCall = <<<EOL
            \$builder
                ->create('{$field->name}', \\{$field->type}::class, {$fieldOptions})
            EOL;

            if (null !== $field->transformer) {
                $builderCall .= <<<EOL
                    ->addModelTransformer({$field->transformer})
                EOL;
            }

            $adds .= <<<EOL
                ->add({$builderCall})

            EOL;
        }

        if ($button = ReflectionUtils::findAttribute($reflection, Button::class)) {
            $submitType = SubmitType::class;
            $buttonOptions = var_export($button->options, true);

            $adds .= <<<EOL
                ->add('submit', \\{$submitType}::class, {$buttonOptions});
            EOL;
        }

        return <<<EOL
        <?php

        namespace {$namespace};

        use Symfony\Component\Form\AbstractType;
        use Symfony\Component\Form\FormBuilderInterface;
        use Symfony\Component\OptionsResolver\OptionsResolver;

        class {$className} extends AbstractType
        {
            public function buildForm(FormBuilderInterface \$builder, array \$options): void
            {
                \$builder
                    {$adds};
            }

            public function configureOptions(OptionsResolver \$resolver): void
            {
                \$resolver->setDefaults({$formOptions});
            }
        }

        EOL;
    }

    /**
     * @return iterable<FormFieldDefinition>
     */
    protected function extractFormFields(ReflectionClass $reflection): iterable
    {
        /** @var ReflectionProperty[] $properties */
        $properties = $reflection->getProperties(ReflectionProperty::IS_PUBLIC);

        foreach ($properties as $property) {
            $propertyType = $property->getType();

            if (null === $propertyType) {
                $propertyType = new UntypedPropertyType;
            }

            if ($propertyType instanceof ReflectionUnionType || $propertyType instanceof ReflectionIntersectionType) {
                throw new LogicException('Union and intersection types are not supported in dynamic forms. Property: '.$reflection->getShortName().'::'.$property->getName()); // for now at least
            }

            $domainConfig = [
                'name' => null,
                'type' => null,
                'options' => [
                    'attr' => [],
                ],
            ];

            $dynamicFormConfig = [
                'name' => null,
                'type' => null,
                'options' => [
                    'attr' => [],
                ],
            ];

            $constraintConfig = [
                'name' => null,
                'type' => null,
                'options' => [
                    'attr' => [],
                ],
            ];

            $defaultConfig = [
                'name' => $property->getName(),
                'type' => null,
                'options' => [
                    'attr' => [],
                    'required' => !$propertyType->allowsNull(),
                ],
            ];

            $attributes = $property->getAttributes();
            $transformer = null;

            if (!$propertyType->isBuiltin()) {
                $propertyClassReflection = new ReflectionClass($propertyType->getName());

                if ($propertyClassReflection->implementsInterface(ScalarPrimitive::class)) {
                    $valueProperty = $propertyClassReflection->getProperty('value');

                    $classAttributes = $propertyClassReflection->getAttributes();
                    $valueAttributes = $valueProperty->getAttributes();

                    $attributes = [...$attributes, ...$classAttributes, ...$valueAttributes];

                    $propertyTypeName = '\\' . $propertyType->getName();
                    $transformer = <<<EOL
                    new \Symfony\Component\Form\CallbackTransformer(
                        fn (?{$propertyTypeName} \$primitive): mixed => \$primitive?->value ?? '',
                        fn (mixed \$value): ?{$propertyTypeName} => empty(\$value) ? null : new {$propertyTypeName}(\$value),
                    )
                    EOL;

                    $propertyType = $valueProperty->getType() ?? new UntypedPropertyType;
                }
            }

            foreach ($attributes as $reflectionAttribute) {
                $attribute = $reflectionAttribute->newInstance();

                if ($attribute instanceof DynamicFormAttribute) {
                    $this->enrichDynamicFormConfig($dynamicFormConfig, $attribute);
                } else if ($attribute instanceof Constraint) {
                    $this->enrichConstraintConfig($constraintConfig, $attribute);
                } else if ($attribute instanceof DomainAttribute) {
                    $this->enrichDomainConfig($domainConfig, $attribute);
                }
            }

            $this->enrichDefaultConfig($defaultConfig, $propertyType, $dynamicFormConfig);

            $config = (new ArrayMerger)->nonDestructive(
                $defaultConfig,
                $constraintConfig,
                $dynamicFormConfig,
                $domainConfig,
            );

            if (!isset($config['options']['label'])) {
                $config['options']['label'] = StringUtils::toTitleCase($property->getName());
            }

            $config['transformer'] = $transformer;

            // dd($config);

            // dd(
            //     $defaultConfig,
            //     $constraintConfig,
            //     $dynamicFormConfig,
            //     new FormFieldDefinition(...$config),
            // );

            yield new FormFieldDefinition(...$config);
        }
    }

    protected function enrichDefaultConfig(array &$config, ReflectionNamedType $type, array $dynamicFormConfig): void
    {
        if (isset($dynamicFormConfig['type'])) {
            return;
        }

        if ($type instanceof UntypedPropertyType) {
            $config['type'] = TextType::class;
            return;
        }

        if ($type->isBuiltin()) {
            switch ($type->getName()) {
                case 'string': $config['type'] = TextType::class; break;
                case 'int': $config['type'] = IntegerType::class; break;
                case 'float': $config['type'] = NumberType::class; break;
                case 'bool': $config['type'] = CheckboxType::class; break;

                case 'DateTime':
                    $config['type'] = DateTimeType::class;
                    $config['options']['input'] = 'datetime';
                    break;

                case 'DateTimeImmutable':
                    $config['type'] = DateTimeType::class;
                    $config['options']['input'] = 'datetime_immutable';
                    break;

                case 'array':
                    $config['type'] = CollectionType::class;
                    // @TODO: read annotations to type entries
                    //        or add new Attribute for that
                    break;

                default: $config['type'] = TextType::class; break;
            };

            return;
        }

        $reflection = new ReflectionClass($type->getName());

        if ($reflection->isEnum()) {
            $config['type'] = EnumType::class;
            $config['options']['class'] = $reflection->getName();
            return;
        }

        if (class_exists(Entity::class) && class_exists(EntityType::class) && ReflectionUtils::hasAttribute($reflection, Entity::class)) {
            $config['type'] = EntityType::class;
            $config['options']['class'] = $reflection->getName();
            return;
        }

        $subFormMetadata = $this->create($type->getName());
        $config['type'] = $subFormMetadata->fqcn;
    }

    private function enrichConstraintConfig(array &$config, Constraint $constraint): void
    {
        switch ($constraint::class) {
            case Url::class: $config['type'] = UrlType::class; break;
            case Email::class: $config['type'] = EmailType::class; break;
            case Choice::class: $config['type'] = ChoiceType::class; break;

            case DateTime::class: $config['type'] = DateTimeType::class; break;
            case Date::class: $config['type'] = DateType::class; break;
            case Time::class: $config['type'] = TimeType::class; break;

            case Image::class: $config['type'] = FileType::class; break;
            case File::class: $config['type'] = FileType::class; break;

            case NotBlank::class: $config['options']['required'] = true; break;

            case Length::class:
                /** @var Length $constraint */
                if (null !== $constraint->min) {
                    $config['options']['attr']['minlength'] = (int) $constraint->min;
                }

                if (null !== $constraint->max) {
                    $config['options']['attr']['maxlength'] = (int) $constraint->max;
                }
                break;

            case Regex::class:
                /** @var Regex $constraint */
                $config['options']['attr']['pattern'] = $constraint->getHtmlPattern();
                break;

            case Range::class:
                /** @var Range $constraint */
                $config['options']['attr']['min'] = (int) $constraint->min;
                $config['options']['attr']['max'] = (int) $constraint->max;
                break;

            case Positive::class: 
                $config['options']['attr']['min'] = 1;
                break;
            case PositiveOrZero::class: 
                $config['options']['attr']['min'] = 0;
                break;

            case Negative::class: 
                $config['options']['attr']['max'] = -1;
                break;
            case NegativeOrZero::class:
                $config['options']['attr']['max'] = 0;
                break;

            case LessThan::class: 
                /** @var LessThan $constraint */
                $config['options']['attr']['max'] = ((int) $constraint->value) - 1;
                break;
            case LessThanOrEqual::class: 
                /** @var LessThanOrEqual $constraint */
                $config['options']['attr']['max'] = (int) $constraint->value;
                break;

            case GreaterThan::class: 
                /** @var GreaterThan $constraint */
                $config['options']['attr']['min'] = (int) $constraint->value + 1;
                break;
            case GreaterThanOrEqual::class: 
                /** @var GreaterThanOrEqual $constraint */
                $config['options']['attr']['min'] = (int) $constraint->value;
                break;
        };
    }

    private function enrichDynamicFormConfig(array &$config, DynamicFormAttribute $attribute): void
    {
        if ($attribute instanceof Name) {
            $config['name'] = $attribute->value;
        } else if ($attribute instanceof Type) {
            $config['type'] = $attribute->value;
        } else if ($attribute instanceof Options) {
            $config['options'] = array_merge_recursive($config['options'], $attribute->value);
        } else if ($attribute instanceof Attributes) {
            $config['options']['attr'] ??= [];
            $config['options']['attr'] = array_merge($config['options']['attr'], $attribute->value);
        } else if ($attribute instanceof OptGroup) {
            $choices = [];
            $toFill = &$choices;

            if (null !== $attribute->label) {
                $choices[$attribute->label] = [];
                $toFill = &$choices[$attribute->label];
            }

            $choicesAttributes = [];

            [$class, $method] = $attribute->provider;
            $items = $attribute->isStatic
                ? $class::{$method}()
                : $this->container->get($class)->{$method}();

            foreach ($items as $item) {
                $toFill[$item->{$attribute->optLabel}] = $item->{$attribute->optValue};
                $choicesAttributes[$item->{$attribute->optLabel}] = is_callable($attribute->attributes)
                    ? ($attribute->attributes)($item)
                    : $attribute->attributes;
            }

            $config['options']['choices'] ??= [];
            $config['options']['choices'] = array_merge($config['options']['choices'], $choices);

            if (!empty($attribute->attributes)) {
                $config['options']['choice_attr'] ??= [];
                $config['options']['choice_attr'] = array_merge($config['options']['choice_attr'], $choicesAttributes);
            }
        }
    }

    private function enrichDomainConfig(array &$config, DomainAttribute $attribute): void
    {
        if ($attribute instanceof Metadata\Name) {
            $config['options']['label'] = $attribute->value;
        } else if ($attribute instanceof Metadata\Description) {
            $config['options']['help'] = $attribute->value;
        } else if ($attribute instanceof Metadata\Example) {
            $config['options']['attr']['placeholder'] = $attribute->value;
        }
    }
}