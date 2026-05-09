<p align="center">
    <img src="formsbundle.png"/>
</p>

[![Build Status](https://img.shields.io/github/check-runs/n-fasano/FormsBundle/main?style=flat-square)](https://github.com/n-fasano/FormsBundle/actions?query=workflow:CI)
[![Codecov](https://img.shields.io/codecov/c/github/n-fasano/FormsBundle/main?style=flat-square)](https://app.codecov.io/gh/n-fasano/FormsBundle)
[![PHP Version](https://img.shields.io/packagist/dependency-v/fasano/forms-bundle/PHP?style=flat-square)](https://packagist.org/packages/fasano/forms-bundle)
[![License](https://img.shields.io/github/license/n-fasano/FormsBundle?style=flat-square)](LICENSE)

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

/** @var FormTypeFactory $formFactory */
$form = $this->formFactory->createForm(ContactRequest::class);
$form->handleRequest($request);

if ($form->isSubmitted() && $form->isValid()) {
    /** @var ContactRequest $data */
    $data = $form->getData();
    // ...
}

return $this->render('contact.html.twig', ['form' => $form]);
```

That's it - no `ContactFormType` class needed. See [here](docs/without-the-bundle.md) what it would look like without the bundle.

## How It Works

`FormTypeFactory` reflects on your DTO, generates a PHP `AbstractType` class, caches it into `%kernel.cache_dir%/forms/`, and `require_once`s it. The generated form type is built by running four configurator layers in sequence — each layer overrides the previous, so later layers take precedence:

1. **Typesystem** — PHP type → form type, nullability → required
2. **Constraints** — `#[Assert\...]` → type refinements and HTML attributes
3. **TypeDocs** — `#[Name]`, `#[Description]`, `#[Example]` → label, help, placeholder
4. **`#[Field\...]` attributes** — explicit per-property overrides, always win

Caching is disabled when `APP_DEBUG` is on, so forms regenerate on every request during development.

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

## Attributes Reference

The bundle adds two namespaces of attributes for explicit control over generated forms:

- **`#[Form\...]`** — class-level, configure the form itself (`Options`, `Button`)
- **`#[Field\...]`** — property-level, override individual fields (`Type`, `Name`, `Options`, `Attributes`, `EntryType`, `OptGroup`)

See [docs/attributes.md](docs/attributes.md) for the full reference with parameters and examples.

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

See [docs/integrations/validator.md](docs/integrations/validator.md) for more details.

## PHPrimitives and TypeDocs support

FormsBundle supports value objects implementing `Fasano\PHPrimitives\Contract\Primitive`. When a property is typed as a Primitive class, the bundle reads its inner `$value` type and attributes, and attaches a `CallbackTransformer` to convert between the wrapper and the raw form value.

`#[Name]`, `#[Description]`, and `#[Example]` from `fasano/typedocs` are **class-level** attributes — they live on the type class itself, not on DTO properties. The bundle reads them from whatever class a property is typed as:

```php
#[Name('Email')]
#[Description('An email address')]
#[Example('john.doe@example.com')]
readonly class Email extends AbstractString implements Primitive { ... }

class MyDto
{
    public Email $email;
    // → label: "Email", help: "An email address", placeholder: "john.doe@example.com"
}
```

See [docs/integrations/typedocs.md](docs/integrations/typedocs.md) and [docs/integrations/primitives.md](docs/integrations/primitives.md) for more details.

## Extensibility

The configurator pipeline is open for extension. You can add custom field configurators to handle project-specific types, or custom constraint configurators to teach the bundle about constraints it doesn't know.

See [docs/extensibility.md](docs/extensibility.md) for full details.

