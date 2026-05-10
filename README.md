<p align="center">
    <img src="formsbundle.png"/>
</p>

[![Build Status](https://img.shields.io/github/check-runs/n-fasano/FormsBundle/main?style=flat-square)](https://github.com/n-fasano/FormsBundle/actions?query=workflow:CI)
[![Codecov](https://img.shields.io/codecov/c/github/n-fasano/FormsBundle/main?style=flat-square)](https://app.codecov.io/gh/n-fasano/FormsBundle)
[![PHP Version](https://img.shields.io/packagist/dependency-v/fasano/forms-bundle/PHP?style=flat-square)](https://packagist.org/packages/fasano/forms-bundle)
[![License](https://img.shields.io/github/license/n-fasano/FormsBundle?style=flat-square)](LICENSE)

Generate Symfony forms directly from your DTOs using PHP attributes - no more hand-writing `FormType` classes.

FormTypes are mostly derived information. Your property types determine the form type, nullability determines required, and validator constraints already encode things like email format, length bounds, and file types.

FormsBundle inspects your DTO's public properties, type hints, and attributes to automatically generate and cache fully functional Symfony forms.

## Same form. Less boilerplate.

Declare the form once on the DTO and skip the hand-written Symfony `FormType`.

### ✅ With the bundle

```php
use Fasano\FormsBundle\Attribute\Form;
use Fasano\FormsBundle\Attribute\Field;
use Symfony\Component\Validator\Constraints as Assert;

#[Form\Options(action: 'app.contact', method: 'POST')]
#[Form\Button(label: 'Request')]
class ContactRequest
{
    #[Assert\Length(min: 2, max: 100)]
    public string $name;

    #[Assert\Email]
    public string $email;

    #[Assert\NotBlank]
    #[Assert\Length(max: 1000)]
    #[Field\Type(TextareaType::class)]
    public string $message;
}
```

Only your DTO remains - the bundle removes the extra boilerplate.

### ❌ Without the bundle

```php
use App\Dto\ContactRequest;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\Constraints\Valid;

class ContactFormType extends AbstractType
{
    public function __construct(
        private UrlGeneratorInterface $urlGenerator
    ) {}

    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void {
        $builder
            ->add('name', TextType::class, [
                'attr' => ['minlength' => 2, 'maxlength' => 100],
                'required' => true,
                'label' => 'Name',
            ])
            ->add('email', EmailType::class, [
                'required' => true,
                'label' => 'Email',
            ])
            ->add('message', TextareaType::class, [
                'attr' => ['maxlength' => 1000],
                'required' => true,
                'label' => 'Message',
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Request',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'action' => $this->urlGenerator->generate('app.contact'),
            'method' => 'POST',
            'data_class' => ContactRequest::class,
            'constraints' => [new Valid()],
            'empty_data' => fn () => new ContactRequest('', '', null),
        ]);
    }
}
```

## Quick Start

### 1. Install the bundle

```bash
composer require fasano/forms-bundle
```

Register the bundle (if not using Symfony Flex):

```php
// config/bundles.php
return [
    // ...
    Fasano\FormsBundle\FormsBundle::class => ['all' => true],
];
```

### 2. Define a DTO

```php
use Fasano\FormsBundle\Attribute\Form;
use Fasano\FormsBundle\Attribute\Field;
use Symfony\Component\Validator\Constraints as Assert;

#[Form\Options(action: 'app.contact', method: 'POST')]
#[Form\Button(label: 'Request')]
class ContactRequest
{
    #[Assert\Length(min: 2, max: 100)]
    public string $name;

    #[Assert\Email]
    public string $email;

    #[Assert\NotBlank]
    #[Assert\Length(max: 1000)]
    #[Field\Type(TextareaType::class)]
    public string $message;
}
```

### 3. Create and use the form

```php
use Fasano\FormsBundle\FormTypeFactory;

#[Route('/contact', name: 'app.contact', methods: ['GET', 'POST'])]
class ContactController extends AbstractController
{
    public function __construct(
        private FormTypeFactory $formFactory,
    ) {}

    public function __invoke(Request $request): Response
    {
        $form = $this->formFactory->createForm(ContactRequest::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData(); // <- ContactRequest

            // ...
        }

        return $this->render('contact.html.twig', ['form' => $form]);
    }
}
```

That's it - no `ContactFormType` class needed.

## How It Works

`FormTypeFactory` reflects on your DTO, generates an `AbstractType` class, and caches it into `%kernel.cache_dir%/forms/`. The generated form type is built by deriving information from four data sources in sequence:

| Layer | Source | Derived information |
|---|---|---|
| Typesystem | PHP types | input type, label, required |
| Constraints | `#[Assert\...]` attributes | input type, HTML attributes |
| TypeDocs | `#[Name]`, `#[Description]`, `#[Example]` | label, help, placeholder |
| FormsBundle | `#[Field\...]`, `#[Form\...]` attributes | anything, always wins |

Caching is disabled when `APP_DEBUG` is on, so forms regenerate on every request during development.

## Documentation

**Reference**
- [Features](docs/features.md) - type inference, nested DTOs, available attributes, constraint enrichment
- [Attributes](docs/attributes.md) - full `#[Form\...]` and `#[Field\...]` reference with parameters and examples

**Integrations**
- [Symfony Validator](docs/integrations/validator.md) - how constraints drive type selection and HTML attributes (72 constraints documented)
- [TypeDocs](docs/integrations/typedocs.md) - automatic labels, help text, and placeholders from type annotations
- [PHPrimitives](docs/integrations/primitives.md) - value object support with automatic data transformers

**Going deeper**
- [Extensibility](docs/extensibility.md) - custom field configurators and constraint configurators
