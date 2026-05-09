# Attributes Reference

## Form-level attributes

These go on the DTO class itself.

---

### `#[Form\Options(...)]`

Sets options on the generated form. Accepts any key-value pairs that Symfony's `OptionsResolver` would accept.

```php
use Fasano\FormsBundle\Attribute\Form;

#[Form\Options(action: 'app_contact', method: 'POST', attr: ['class' => 'my-form'])]
class ContactRequest { ... }
```

If `action` is provided, it is treated as a **route name** and resolved via the router at form generation time.

---

### `#[Form\Button(...)]`

Adds a submit button to the form. Accepts any options valid for Symfony's `SubmitType`.

```php
#[Form\Button(label: 'Send Message', attr: ['class' => 'btn btn-primary'])]
class ContactRequest { ... }
```

---

## Field-level attributes

These go on individual public properties of the DTO.

---

### `#[Field\Type(string $value)]`

Overrides the inferred form type. Takes a fully-qualified class name.

```php
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

#[Field\Type(TextareaType::class)]
public string $bio;
```

---

### `#[Field\Name(string $value)]`

Overrides the field name used in the form. Useful when the DTO property name differs from what you want rendered.

```php
#[Field\Name('full_name')]
public string $name;
```

---

### `#[Field\Options(...)]`

Merges additional options into the field configuration. Accepts any key-value pairs valid for the field's form type.

```php
#[Field\Options(label: 'Your email', help: 'We will never share it.', row_attr: ['class' => 'mb-3'])]
public string $email;
```

---

### `#[Field\Attributes(...)]`

Sets HTML attributes on the field's `attr` option. Shorthand for `#[Field\Options(attr: [...])]`.

```php
#[Field\Attributes(class: 'form-control', placeholder: 'e.g. John')]
public string $name;
```

---

### `#[Field\EntryType(string $class)]`

Specifies the entry type for a `CollectionType` field. Required when the bundle cannot infer the entry type from the property's type hint.

```php
#[Field\EntryType(TagType::class)]
public array $tags = [];
```

---

### `#[Field\OptGroup(...)]`

Adds choice grouping to a select field. The `provider` is a callable that returns the choices — either a static method or a service method.

```php
#[Field\OptGroup(
    provider: [StatusEnum::class, 'cases'],
    label: 'Status',
    optLabel: 'name',   // which property to use as the option label
    optValue: 'value',  // which property to use as the option value
)]
public ?string $status = null;
```

Set `isStatic: false` to resolve the provider from the service container:

```php
#[Field\OptGroup(
    provider: [CountryRepository::class, 'findAllActive'],
    label: 'Country',
    optLabel: 'name',
    optValue: 'code',
    isStatic: false,
)]
public ?string $country = null;
```
