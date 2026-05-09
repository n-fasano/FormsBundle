# Extensibility

## Custom field configurators

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

## Custom constraint configurators

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

## Changing the cache directory

By default, generated form classes are written to `%kernel.cache_dir%/forms/` under the namespace `Cache\Forms`. Override the `fasano_forms.form_type_naming_strategy` service to change either:

```yaml
# config/services.yaml
fasano_forms.form_type_naming_strategy:
    class: Fasano\FormsBundle\Form\FormTypeNamingStrategy
    arguments:
        $namespace: 'App\Cache\Forms'
        $directory: '%kernel.cache_dir%/my_forms/'
```

## Debugging generated forms

In development, generated form classes are written to `var/cache/<env>/forms/` (or your custom directory). You can inspect them directly to understand what the bundle produced for a given DTO — the files are plain PHP `AbstractType` classes.
