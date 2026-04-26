# Changelog

## 1.0.0 — 2026-04-26

Initial release.

### Features

- Generate Symfony `FormType` classes automatically from DTO classes using PHP 8 attributes
- Infers field types from property type hints and nullable modifiers
- Maps 41 of 72 Symfony Validator constraints to form options (HTML attributes, field types, choices)
- Supports nested objects (recursive sub-form generation), collections, enums, file uploads, and Doctrine entities
- Integrates with `fasano/typedocs` attributes (`#[Name]`, `#[Description]`, `#[Example]`) for labels and help text
- Integrates with `fasano/phprimitives` value objects via generated data transformers
- Extensible configurator pipeline via tagged services — add custom `FieldConfigurator` implementations at any priority
- `#[Field\...]` and `#[Form\...]` attributes for explicit per-field and form-level overrides
- `FormTypeFactory` service for on-demand form creation from any DTO class
- `#[MapFormPayload]` attribute for automatic form binding in Symfony controllers
- Generated form classes are cached in `%kernel.cache_dir%/forms/` (disabled in debug mode)
