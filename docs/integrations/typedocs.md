# TypeDocs Integration

FormsBundle recognizes attributes from the `fasano/typedocs` library and uses them to set labels, help text, and placeholders on generated form fields.

`#[Name]`, `#[Description]`, and `#[Example]` are **class-level** attributes (`#[Attribute(Attribute::TARGET_CLASS)]`). They describe a type, not a property. The bundle reads them from the class that a property is typed as.

## Where they apply

### On Primitive classes

When a DTO property is typed as a Primitive class, the bundle reads TypeDoc attributes from that class:

```php
use Fasano\PHPrimitives\Contract\Primitive;
use Fasano\Typedocs\Name;
use Fasano\Typedocs\Description;
use Fasano\Typedocs\Example;

#[Name('Email Address')]
#[Description('A valid email address for the account holder.')]
#[Example('jane@example.com')]
readonly class EmailAddress extends AbstractString implements Primitive { ... }

class RegisterDto
{
    public EmailAddress $email;
    // → label: "Email Address", help: "A valid email address for the account holder.", placeholder: "jane@example.com"
}
```

### On DTO classes used as sub-forms

When a DTO is embedded as a sub-form inside another DTO, TypeDoc attributes on the inner DTO describe the sub-form field:

```php
#[Name('Shipping Address')]
#[Description('Where should we deliver your order?')]
class AddressDto
{
    public string $street;
    public string $city;
}

class OrderDto
{
    public AddressDto $shippingAddress;
    // → label: "Shipping Address", help: "Where should we deliver your order?"
}
```

## Attribute Mapping

| TypeDoc Attribute | Form Option | Purpose |
|---|---|---|
| `#[Name('...')]` | `label` | Sets the field's visible label |
| `#[Description('...')]` | `help` | Sets the help text shown below the field |
| `#[Example('...')]` | `attr.placeholder` | Sets the placeholder text inside the input |

## Precedence

TypeDocs attributes have lower priority than explicit `#[Field\...]` attributes. If both are present, `#[Field\Options(label: '...')]` wins.

If no label is provided by any source, the bundle falls back to a title-cased version of the property name (e.g., `shippingAddress` → "Shipping Address").
