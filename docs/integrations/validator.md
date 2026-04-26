# Symfony Validator Integration

FormsBundle automatically reads Symfony Validator constraints (`#[Assert\...]`) from your DTO properties and uses them to enrich the generated form - inferring more specific form types and setting HTML attributes for client-side hints.

This happens transparently. If a constraint is present on a property, the bundle picks it up. No configuration needed.

## How It Works

Validator constraints are applied *after* type inference but *before* explicit `#[Field\...]` attributes, meaning they refine the defaults but can always be overridden manually.

## Type Inference from Constraints

Certain constraints cause the bundle to select a more specific form type than what the PHP type alone would suggest:

| Constraint | Inferred Form Type |
|---|---|
| `#[Assert\Email]` | `EmailType` |
| `#[Assert\Url]` | `UrlType` |
| `#[Assert\Choice]` | `ChoiceType` |
| `#[Assert\DateTime]` | `DateTimeType` |
| `#[Assert\Date]` | `DateType` |
| `#[Assert\Time]` | `TimeType` |
| `#[Assert\File]` | `FileType` |
| `#[Assert\Image]` | `FileType` |

For example, a `string` property normally renders as `TextType`, but adding `#[Assert\Email]` upgrades it to `EmailType` automatically.

> [!NOTE]
> The constraints listed above are a starting point - a proof of concept to show that Validator constraints too can drive form generation. There are more constraints that could be mapped, and contributions or suggestions for additional ones are welcome.

## HTML Attribute Enrichment

Other constraints set HTML attributes on the rendered field, providing built-in browser validation:

| Constraint | HTML Attribute |
|---|---|
| `#[Assert\NotBlank]` | `required="true"` |
| `#[Assert\Length(min: 3, max: 50)]` | `minlength="3" maxlength="50"` |
| `#[Assert\Range(min: 1, max: 100)]` | `min="1" max="100"` |
| `#[Assert\Regex]` | `pattern="..."` (using the constraint's HTML pattern) |
| `#[Assert\Positive]` | `min="1"` |
| `#[Assert\PositiveOrZero]` | `min="0"` |
| `#[Assert\Negative]` | `max="-1"` |
| `#[Assert\NegativeOrZero]` | `max="0"` |
| `#[Assert\GreaterThan(5)]` | `min="6"` |
| `#[Assert\GreaterThanOrEqual(5)]` | `min="5"` |
| `#[Assert\LessThan(10)]` | `max="9"` |
| `#[Assert\LessThanOrEqual(10)]` | `max="10"` |

## Example

```php
use Symfony\Component\Validator\Constraints as Assert;

class ProductDto
{
    #[Assert\Length(min: 2, max: 100)]
    public string $name;
    // -> TextType, minlength=2, maxlength=100

    #[Assert\Email]
    public string $contactEmail;
    // -> EmailType

    #[Assert\Positive]
    #[Assert\LessThanOrEqual(9999)]
    public int $quantity;
    // -> IntegerType, min=1, max=9999

    #[Assert\Url]
    public ?string $website = null;
    // -> UrlType, required=false
}
```

## Precedence

If both a constraint and a `#[Field\Type(...)]` attribute suggest a form type, the explicit `#[Field\Type]` always wins. Constraints fill in what isn't explicitly specified - they never override explicit bundle attributes.