# FormsBundle

Generate Symfony forms directly from your DTOs using PHP attributes - no more hand-writing `FormType` classes.

FormTypes are mostly derived information. Your property types determine the form type, nullability determines required, and validator constraints already encode things like email format, length bounds, and file types.

FormsBundle inspects your DTO's public properties, type hints, and attributes to automatically generate and cache a fully functional Symfony `AbstractType` at runtime.

## Installation

```bash
composer require fasano/forms-bundle
```

Register the bundle (if not using Symfony Flex):

```php
// config/bundles.php
return [
    // ...
    Fasano\FormsBundle\FormsBundle::class => ['all' => true],
];
```

## Quick Start

### 1. Define a DTO

```php
use Fasano\FormsBundle\Attribute\Form;
use Fasano\FormsBundle\Attribute\Field;
use Symfony\Component\Validator\Constraints as Assert;

#[Form\Options(action: 'app.contact', method: 'POST')]
#[Form\Button(label: 'Request')]
class ContactRequest
{
    #[Assert\Length(min: 2, max: 100)]
    public string $name;

    #[Assert\Email]
    public string $email;

    #[Assert\NotBlank]
    #[Assert\Length(max: 1000)]
    public ?string $message = null;
}
```

### 2. Create and use the form

```php
use Fasano\FormsBundle\FormTypeFactory;

class ContactController
{
    public function __construct(private FormTypeFactory $formFactory) {}

    public function contact(Request $request): Response
    {
        $form = $this->formFactory->createForm(ContactRequest::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var ContactRequest $data */
            $data = $form->getData();
            // ...
        }

        return $this->render('contact.html.twig', ['form' => $form]);
    }
}
```

That's it - no `ContactFormType` class needed.

## How It Works

`FormTypeFactory` reflects on your DTO and generates a cached `AbstractType` PHP file. The generated form type is built from three layers of information, merged in order of priority:

1. **Typesystem config** - inferred from property names, type hints, and nullability
2. **Constraint config** - derived from Symfony Validator constraints (`#[Assert\...]`)
3. **Typedocs config** - labels, help text, and placeholders from TypeDoc attributes
4. **FormsBundle config** - explicit overrides via `#[Field\...]` attributes

## Type Inference

The bundle automatically maps PHP types to Symfony form types:

| PHP Type | Form Type |
|---|---|
| `string` | `TextType` |
| `int` | `IntegerType` |
| `float` | `NumberType` |
| `bool` | `CheckboxType` |
| `array` | `CollectionType` |
| Enum classes | `EnumType` |
| Doctrine entities | `EntityType` |
| Other objects | Recursively generated sub-form |

Nullable types automatically set `required: false`.

## Constraint-Driven Enrichment

Symfony Validator constraints on properties enhance the generated form:

| Constraint | Effect |
|---|---|
| `#[Assert\Email]` | Sets `EmailType` |
| `#[Assert\Url]` | Sets `UrlType` |
| `#[Assert\Length(min: 3, max: 50)]` | Adds `minlength` / `maxlength` HTML attributes |
| `#[Assert\Range(min: 1, max: 100)]` | Adds `min` / `max` HTML attributes |
| `#[Assert\Regex]` | Adds `pattern` HTML attribute |
| `#[Assert\NotBlank]` | Sets `required: true` |
| `#[Assert\Positive]` | Sets `min: 1` |
| `#[Assert\File]` / `#[Assert\Image]` | Sets `FileType` |
| `#[Assert\Date]` / `#[Assert\Time]` / `#[Assert\DateTime]` | Sets corresponding date/time types |

## Attributes Reference

### Form-Level Attributes

**`#[Form\Options(...)]`** - Set form-level options like `action`, `method`, etc. If `action` is provided, it's treated as a route name and resolved via the router.
**`#[Form\Button(label: 'Submit')]`** - Adds a submit button to the form.

### Field-Level Attributes

**`#[Field\Type(SomeType::class)]`** - Override the inferred form type.
**`#[Field\Name('custom_name')]`** - Override the field name.
**`#[Field\Options(label: 'Custom Label', help: '...')]`** - Merge additional options into the field configuration.
**`#[Field\Attributes(class: 'form-control', id: 'my-field')]`** - Set HTML attributes on the field's `attr` option.
**`#[Field\EntryType(...)]`** - Specify entry type for collections.
**`#[Field\OptGroup(...)]`** - Add choice groups for select fields. Supports static methods and service-based providers:

```php
#[Field\OptGroup(
    provider: [StatusEnum::class, 'cases'],
    label: 'Statuses',
    optLabel: 'name',
    optValue: 'value',
)]
public ?string $status = null;
```

Set `isStatic: false` to resolve the provider from the service container instead.

## Nested Objects

When a property is typed as a class that isn't an enum or a Doctrine entity, the bundle recursively generates a sub-form for that class. This means your DTOs can compose naturally:

```php
class AddressDto
{
    public string $street;
    public string $city;
    public string $zip;
}

class OrderDto
{
    public string $reference;
    public AddressDto $shippingAddress; // Renders as an embedded sub-form
}
```

## PHPrimitives and TypeDocs support

FormsBundle supports value objects implementing `Fasano\PHPrimitives\Contract\Primitive`. When a property is typed as a Primitive class, the bundle will read its attributes, and attach a `CallbackTransformer` to convert between the primitive wrapper and the raw form value.

The following attributes from `foundation/typedocs` are recognized:

- `#[TypeDocs\Name('...')]` -> sets the field label
- `#[TypeDocs\Description('...')]` -> sets the help text
- `#[TypeDocs\Example('...')]` -> sets the placeholder

```php
#[Name('Email')]
#[Description('An email address')]
#[Example('john.doe@example.com')]
readonly class Email extends AbstractString
{
    public static function validate(string $value)
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException(sprintf(
                '"%s" is not a valid email',
                $value,
            ));
        }
    }
}

class MyDto
{
    /**
     * The form field will have:
     * - "Email" as label
     * - "An email address" as help text
     * - "john.doe@example.com" as placeholder
     */
    public Email $email;
}
```

## Extensibility

### Custom field configurators

Implement `FieldConfigurator` to inject project-specific logic into the analysis pipeline:

```php
use Fasano\FormsBundle\Field\FieldConfigurator;
use Fasano\FormsBundle\Field\FieldConfig;
use Fasano\FormsBundle\Field\FieldContext;

class MoneyConfigurator implements FieldConfigurator
{
    public function configure(FieldContext $context): FieldConfig
    {
        $config = new FieldConfig();

        if ($context->type->getName() === Money::class) {
            $config->type = MoneyType::class;
        }

        return $config;
    }
}
```

Register it as a service and it will be auto-tagged with `forms.field_configurator` at priority `0`, placing it after the built-in type/constraint/typedoc configurators but before explicit `#[Field\...]` attributes — so it can still be overridden per-property.

To control its position in the pipeline, set the tag priority explicitly. Lower priority = runs later = wins:

| Priority | Configurator |
|---|---|
| `30` | `TypesystemConfigurator` (PHP type inference) |
| `20` | `ConstraintsConfigurator` (validator constraints) |
| `10` | `TypedocsConfigurator` (labels, help, placeholders) |
| `0` | User configurators (default) |
| `-10` | `FormsBundleConfigurator` (`#[Field\...]` attributes — always wins) |

```yaml
# config/services.yaml
App\Form\MoneyConfigurator:
    tags:
        - { name: forms.field_configurator, priority: 15 } # after constraints, before typedocs
```

### Custom constraint configurators

To add support for a Symfony Validator constraint not handled by the bundle, implement `ConstraintConfigurator`:

```php
use Fasano\FormsBundle\Configurator\Constraint\ConstraintConfigurator;
use Fasano\FormsBundle\Field\FieldConfig;
use Fasano\FormsBundle\Field\FieldContext;

class AcmeConstraintConfigurator implements ConstraintConfigurator
{
    public function constraint(): string
    {
        return AcmeConstraint::class;
    }

    public function configure(FieldConfig $config, object $constraint, FieldContext $context): void
    {
        $config->options['attr']['data-acme'] = $constraint->value;
    }
}
```

It will be auto-tagged and picked up by `ConstraintsConfigurator` automatically.

## Caching

Generated form type classes are cached under `%kernel.cache_dir%/forms/`. Caching is disabled when `APP_DEBUG` is on, so forms regenerate on every request during development. In production, forms are generated once and loaded via `require_once`.

## Requirements

- PHP 8.2+
- Symfony 8.0

## License

MIT