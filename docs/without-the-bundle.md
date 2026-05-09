# What it looks like without the bundle

The Quick Start DTO:

```php
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
    public ?string $message = null;
}
```

Without the bundle, you would write this by hand:

```php
<?php

namespace App\Form;

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
    public function __construct(private UrlGeneratorInterface $urlGenerator) {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => ['minlength' => 2, 'maxlength' => 100],
            ])
            ->add('email', EmailType::class)
            ->add('message', TextareaType::class, [
                'required' => false,
                'attr' => ['maxlength' => 1000],
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

And that's before your DTOs grow more fields, gain nested objects, or use primitives.
