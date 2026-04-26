# Symfony Validator Constraints Coverage

72 instantiable constraint classes. 41 handled (✅), 31 not handled (❌).

| Constraint | Handled | Mapping | Notes |
|---|:---:|---|---|
| `All` | ❌ | — | Meta: applies constraints to each element of an array |
| `AtLeastOneOf` | ❌ | — | Meta: at least one of several constraints must pass |
| `Bic` | ✅ | `attr['pattern']` | BIC/SWIFT format (4 letters + 2 letters + 2 alnum + optional 3 alnum) |
| `Blank` | ❌ | — | Field must be empty; no practical form use case |
| `Callback` | ❌ | — | Custom validation logic; cannot be statically configured |
| `CardScheme` | ✅ | `attr['pattern']` | Per-scheme regex union from `schemes`; falls back to `[0-9]{12,19}` for unknown schemes |
| `Cascade` | ❌ | — | Meta: cascades validation to embedded objects |
| `Charset` | ❌ | — | Character encoding; no HTML equivalent |
| `Choice` | ✅ | `ChoiceType` | Maps `choices` array and `multiple` flag |
| `Cidr` | ✅ | `attr['pattern']` | IP/prefix-length format; v4/v6/all based on `version` |
| `Collection` | ❌ | — | Validates structure of a specific nested array; sub-form generation would need explicit field definitions |
| `Count` | ❌ | — | Array item count; no HTML attribute |
| `Country` | ✅ | `CountryType` | Full localised country select |
| `CssColor` | ❌ | — | HTML `type="color"` only accepts `#RRGGBB`; too restrictive for the full CSS color space |
| `Currency` | ✅ | `CurrencyType` | Full currency select |
| `Date` | ✅ | `DateType` | |
| `DateTime` | ✅ | `DateTimeType` | |
| `DivisibleBy` | ✅ | `attr['step']` | |
| `Email` | ✅ | `EmailType` | |
| `EqualTo` | ❌ | — | Cross-field or fixed-value comparison; server-side only |
| `Expression` | ❌ | — | Symfony expression language; dynamic logic, cannot be statically configured |
| `ExpressionSyntax` | ❌ | — | Validates that the value is valid Symfony expression syntax; no form mapping |
| `File` | ✅ | `FileType` | Maps `mimeTypes` and `extensions` to `attr['accept']` |
| `GreaterThan` | ✅ | `attr['min']` | Min is `constraint + 1` |
| `GreaterThanOrEqual` | ✅ | `attr['min']` | |
| `Hostname` | ✅ | `attr['pattern']` | Two variants based on `requireTld` |
| `Iban` | ✅ | `attr['pattern']` | 2-letter country + 2-digit check + up to 30 alphanumeric |
| `IdenticalTo` | ❌ | — | Cross-field or fixed-value strict equality; server-side only |
| `Image` | ✅ | `FileType` | Maps `mimeTypes`/`extensions` to `attr['accept']`; defaults to `image/*` |
| `Ip` | ✅ | `attr['pattern']` | v4/v6/all variants based on `version` property |
| `Isbn` | ✅ | `attr['pattern']` | ISBN-10, ISBN-13, or both based on `type` |
| `IsFalse` | ❌ | — | Checkbox that must be unchecked; unusual form use case with no HTML enforcement |
| `Isin` | ❌ | — | Securities identification number; niche, no Symfony form type |
| `IsNull` | ❌ | — | Value must be null; no practical form use case |
| `Issn` | ❌ | — | Serial number format; niche, no Symfony form type |
| `IsTrue` | ✅ | `CheckboxType` | |
| `Json` | ✅ | `TextareaType` | JSON is typically multi-line |
| `Language` | ✅ | `LanguageType` | Full localised language select |
| `Length` | ✅ | `attr['minlength']`, `attr['maxlength']` | |
| `LessThan` | ✅ | `attr['max']` | Max is `constraint - 1` |
| `LessThanOrEqual` | ✅ | `attr['max']` | |
| `Locale` | ✅ | `LocaleType` | Full localised locale select |
| `Luhn` | ❌ | — | Luhn check digit algorithm; cannot be expressed as a static pattern |
| `MacAddress` | ✅ | `attr['pattern']` | Colon- or dash-separated hex pairs |
| `Negative` | ✅ | `attr['max']` | Max is `-1` |
| `NegativeOrZero` | ✅ | `attr['max']` | Max is `0` |
| `NoSuspiciousCharacters` | ❌ | — | Unicode confusable character detection; no HTML equivalent |
| `NotBlank` | ✅ | `required = true` | |
| `NotCompromisedPassword` | ✅ | `PasswordType` | Indicates the field is a password |
| `NotEqualTo` | ❌ | — | Must not equal a value; server-side only |
| `NotIdenticalTo` | ❌ | — | Must not be strictly identical; server-side only |
| `NotNull` | ✅ | `required = true` | |
| `PasswordStrength` | ✅ | `PasswordType` | Indicates the field is a password |
| `Positive` | ✅ | `attr['min']` | Min is `1` |
| `PositiveOrZero` | ✅ | `attr['min']` | Min is `0` |
| `Range` | ✅ | `attr['min']`, `attr['max']` | |
| `Regex` | ✅ | `attr['pattern']` | PHP delimiters stripped and pattern converted to HTML5 format |
| `Sequentially` | ❌ | — | Meta: applies constraints in sequence, stopping on first failure |
| `Time` | ✅ | `TimeType` | |
| `Timezone` | ✅ | `TimezoneType` | Full localised timezone select |
| `Traverse` | ❌ | — | Meta: traverses and validates nested objects/collections |
| `Type` | ❌ | — | PHP type check; coercion is handled transparently by the form system |
| `Ulid` | ✅ | `attr['pattern']` | Crockford base32, exactly 26 characters |
| `Unique` | ❌ | — | Collection element uniqueness; no HTML equivalent |
| `Url` | ✅ | `UrlType` | |
| `Uuid` | ✅ | `attr['pattern']` | Standard 8-4-4-4-12 hex pattern |
| `Valid` | ❌ | — | Meta: cascades validation into an embedded object |
| `Video` | ❌ | — | Would map to `FileType` + `accept="video/*"`, but requires `symfony/process` |
| `Week` | ❌ | — | No `WeekType` in Symfony Form; HTML `type="week"` has poor browser support |
| `When` | ❌ | — | Conditional constraint; depends on runtime context |
| `WordCount` | ❌ | — | Word count; no HTML attribute; requires `ext-intl` |
| `Yaml` | ❌ | — | Could map to `TextareaType` like `Json`, but YAML has no dedicated HTML semantic |
