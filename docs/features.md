# Features

## Type Inference

The bundle automatically maps PHP types to Symfony form types:

| PHP Type | Form Type |
|---|---|
| `string` | `TextType` |
| `int` | `IntegerType` |
| `float` | `NumberType` |
| `bool` | `CheckboxType` |
| `array` | `CollectionType` |
| `DateTime` | `DateTimeType` |
| `DateTimeImmutable` | `DateTimeType` |
| `UploadedFile` | `FileType` |
| Enum classes | `EnumType` |
| Other objects | Recursively generated sub-form |

Non-nullable types automatically set `required: true`.

## Nested Objects

When a property is typed as a class that isn't one of the natively supported types (enum, `DateTime`, `DateTimeImmutable`, `UploadedFile`), the bundle recursively generates a sub-form for that class. This means your DTOs can compose naturally:

```php
// AddressForm.php
class AddressForm
{
    public string $street;
    public string $city;
    public string $zip;
}

// OrderForm.php
class OrderForm
{
    public string $reference;
    public AddressForm $shippingAddress; // Resolved as an embedded sub-form
}
```

## Attributes Reference

The bundle adds two namespaces of attributes for explicit control over generated forms:

- **`#[Form\...]`** — class-level, configure the form itself (`Options`, `Button`)
- **`#[Field\...]`** — property-level, override individual fields (`Type`, `Name`, `Options`, `Attributes`, `EntryType`, `OptGroup`)

See [attributes.md](attributes.md) for the full reference with parameters and examples.

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

See [integrations/validator.md](integrations/validator.md) for full coverage.

## PHPrimitives and TypeDocs Support

FormsBundle supports value objects implementing `Fasano\PHPrimitives\Contract\Primitive`. When a property is typed as a Primitive class, the bundle reads its inner `$value` type and attributes, and attaches a `CallbackTransformer` to convert between the wrapper and the raw form value.

`#[Name]`, `#[Description]`, and `#[Example]` from `fasano/typedocs` are **class-level** attributes — they live on the type class itself, not on DTO properties. The bundle reads them from whatever class a property is typed as:

```php
// Email.php
#[Name('Email')]
#[Description('An email address')]
#[Example('john.doe@example.com')]
readonly class Email extends AbstractString implements Primitive { ... }

// MyForm.php
class MyForm
{
    public Email $email;
    // → label: "Email", help: "An email address", placeholder: "john.doe@example.com"
}
```

See [integrations/typedocs.md](integrations/typedocs.md) and [integrations/primitives.md](integrations/primitives.md) for more details.

## Extensibility

The configurator pipeline is open for extension. You can add custom field configurators to handle project-specific types, or custom constraint configurators to teach the bundle about constraints it doesn't know.

See [extensibility.md](extensibility.md) for full details.
