# Architecture Notes

## What This Bundle Does

`fasano/forms-bundle` generates Symfony `AbstractType` PHP classes at runtime from plain DTO classes annotated with PHP 8 attributes. You define a DTO with typed properties and attributes, call `FormTypeFactory::createForm(MyDto::class)`, and the bundle reads the DTO's reflection data, generates PHP source code for a form type, writes it to the cache directory, requires it, and returns a `TypedForm<T>` wrapping the standard Symfony form.

---

## Data Flow

```
FormTypeFactory::createForm(MyDto::class)
  └─> FormTypeGenerator::generate(MyDto::class)
      └─> for each public property:
            FieldAnalyzer::analyze(ReflectionProperty)
            1. Primitive check: type resolution, attribute merge, transformer capture
            2. Build FieldContext(type, attributes, factory, property)
            3. array_reduce over configurators via FieldConfig::override() — last wins
                  ├─ TypesystemConfigurator   (priority  30) # PHP type -> form type, required
                  ├─ ConstraintsConfigurator  (priority  20) # validator constraints -> type hints / HTML attrs
                  ├─ TypedocsConfigurator     (priority  10) # typedoc attributes -> label, help, placeholder
                  ├─ [user configurators]     (priority   0) # project-specific overrides
                  └─ FormsBundleConfigurator  (priority -10) # explicit #[Field\...] attributes -> always wins
            returns FieldDefinition(name, type, options, transformer?)
```

`FormTypeGenerator` uses `CodeWriter` + `FormTypeClassTemplate` + `EmptyDataTemplate` + `PrimitiveTransformerTemplate` to produce a PHP source string, which `FormTypeFactory` writes to `%kernel.cache_dir%/forms/XxxType.php` and `require_once`s.

---

## Directory Structure

```
config/
└── services.yaml           # DI wiring
src/
├── Attribute/          # PHP 8 attributes for DTOs (Field/, Form/)
├── Configurator/       # Field analysis pipeline
│   ├── BundleAttribute/    # BundleAttributeConfigurator interface + 5 implementations
│   ├── Constraint/         # ConstraintConfigurator interface + 15+ implementations
│   ├── Typedoc/            # TypedocConfigurator interface + 3 implementations
│   ├── ConstraintsConfigurator.php     # registry dispatcher
│   ├── FormsBundleConfigurator.php     # registry dispatcher
│   ├── TypedocsConfigurator.php        # registry dispatcher
│   └── TypesystemConfigurator.php      # standalone: PHP type system -> form type
├── Field/              # Core field analysis value objects and pipeline interface
│   ├── FieldAnalyzer.php, FieldConfig.php, FieldConfigurator.php
│   ├── FieldContext.php, FieldDefinition.php
├── Form/               # Form-level generation and decorators
│   ├── TypedForm.php, ErroredForm.php
│   ├── FormTypeGenerator.php, FormTypeGeneratorFactory.php, FormTypeMetadata.php
├── Http/               # Symfony request layer
│   ├── FormValueResolver.php, MapFormPayload.php
├── Reflection/         # Fake ReflectionNamedType stubs for type erasure
├── Template/           # Code generation templates
└── Toolbox/            # Utilities (ArrayMerger, CodeWriter, StringUtils, …)
```

---

## Configurator Pattern

All configurators implement `FieldConfigurator` and are called uniformly via `array_reduce` in `FieldAnalyzer`. Each returns a `FieldConfig`; later configurators have higher priority via `FieldConfig::override()`.

Configurators are wired via the `forms.field_configurator` tag. Lower tag priority = runs later = wins. The four built-in configurators occupy priorities `30` down to `-10`; user-defined configurators default to `0`, placing them between `TypedocsConfigurator` and `FormsBundleConfigurator`.

The three registry-based configurators (`ConstraintsConfigurator`, `FormsBundleConfigurator`, `TypedocsConfigurator`) each hold a map of `attribute class -> sub-configurator` and dispatch to the matching implementation. Sub-configurators are auto-tagged via `_instanceof` in `services.yaml`.

---

## Options Considered

These Symfony classes do similar things to FormsBundle:  
- vendor/symfony/form/Extension/Validator/ValidatorTypeGuesser.php
- vendor/symfony/form/EnumFormTypeGuesser.php
