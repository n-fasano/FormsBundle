# FormsBundle Documentation

Generate Symfony forms directly from your DTOs — no `FormType` classes needed.

## Contents

- [Architecture](architecture.md) — data flow, configurator pipeline, directory structure
- [Attributes](attributes.md) — full reference for `#[Form\...]` and `#[Field\...]` attributes
- [Extensibility](extensibility.md) — custom field configurators, custom constraint configurators

## Integrations

- [Symfony Validator](integrations/validator.md) — how constraints drive type inference and HTML attributes
- [TypeDocs](integrations/typedocs.md) — labels, help text, and placeholders from TypeDoc attributes
- [PHPrimitives](integrations/primitives.md) — value object support with automatic data transformers
