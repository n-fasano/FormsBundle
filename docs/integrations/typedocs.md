# TypeDocs Integration

FormsBundle recognizes attributes from the `foundation/typedocs` library and uses them to set labels, help text, and placeholders on generated form fields.

This is especially useful when your DTOs already carry domain documentation via TypeDocs - the same metadata that describes your types to other developers also drives the form UI, keeping things consistent without duplication.

## Attribute Mapping

| TypeDoc Attribute | Form Option | Purpose |
|---|---|---|
| `#[Name('...')]` | `label` | Sets the field's visible label |
| `#[Description('...')]` | `help` | Sets the help text shown below the field |
| `#[Example('...')]` | `attr.placeholder` | Sets the placeholder text inside the input |

## Example

```php
use Fasano\Typedocs\Name;
use Fasano\Typedocs\Description;
use Fasano\Typedocs\Example;

class InviteDto
{
    #[Name('Recipient Email')]
    #[Description('The email address of the person you want to invite.')]
    #[Example('jane@example.com')]
    public string $email;

    #[Name('Personal Note')]
    #[Description('An optional message included in the invitation email.')]
    public ?string $note = null;
}
```

This generates a form where `email` has the label "Recipient Email", help text explaining its purpose, and a placeholder showing an example value - all without any `#[Field\Options(...)]` attributes.

## Precedence

TypeDocs attributes are applied with the lowest priority in the merge order. If a `#[Name]` attribute and a `#[Field\Options(label: '...')]` attribute are both present on the same property, the `#[Name]` value is ignored.

If no label is provided by any source, the bundle falls back to a title-cased version of the property name (e.g., `shippingAddress` -> "Shipping Address").