# Primitive Value Objects

FormsBundle supports value objects that implement `Fasano\PHPrimitives\Contract\Primitive`. When a DTO property is typed as a Primitive class, the bundle automatically unwraps it - using the inner `value` property's type and attributes for form generation, and attaching a `CallbackTransformer` to handle the conversion.

## How It Works

Given a Primitive like this:

```php
use Fasano\PHPrimitives\Contract\Primitive;
use Fasano\Typedocs\Name;
use Fasano\Typedocs\Example;

#[Name('Email Address')]
#[Example('jane@example.com')]
class Email implements Primitive
{
    public function __construct(
        #[Assert\Email]
        public readonly string $value,
    ) {}
}
```

When used in a DTO:

```php
class ContactDto
{
    #[Field\Type(EmailType::class)]
    public Email $email;
}
```

Any attributes on the primitive class and its value property are merged into the field configuration as if they were placed directly on the DTO's property.

The bundle will:

1. Detect that `Email` implements `Primitive`.
2. Collect attributes from the DTO's property (`#[Field\Type(EmailType::class)]`), the Primitive class itself (`#[Name]`, `#[Example]`), and its inner `$value` property (`#[Assert\Email]`).
3. Read the type hint from `$value` (`string`) and merge all collected attributes into the field configuration.
4. Generate an `EmailType` field with label "Email Address" and placeholder "jane@example.com".
5. Attach a `CallbackTransformer` that converts between `Email` and the raw string value for the form.

The transformer handles null gracefully - a blank input returns `null`, and a `null` Primitive renders as an empty field.