Before: 

```php
class RegistrationDto
{
    #[Assert\Length(min: 2, max: 100)]
    public string $name;

    #[Assert\Email]
    public string $email;

    #[Assert\Length(min: 8)]
    public string $password;

    #[Assert\Range(min: 18, max: 120)]
    public int $age;

    #[Assert\Url]
    public ?string $website = null;
}

class RegistrationFormType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => RegistrationDto::class]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => ['minlength' => 2, 'maxlength' => 100],
            ])
            ->add('email', EmailType::class)
            ->add('password', PasswordType::class, [
                'attr' => ['minlength' => 8],
            ])
            ->add('age', IntegerType::class, [
                'attr' => ['min' => 18, 'max' => 120],
            ])
            ->add('website', UrlType::class, [
                'required' => false,
            ]);
    }
}
```

After:

```php
// RegistrationDto.php — #[Field\Type] added to hint the form type for password. No FormType needed.
class RegistrationDto
{
    #[Assert\Length(min: 2, max: 100)]
    public string $name;

    #[Assert\Email]
    public string $email;

    #[Assert\Length(min: 8)]
    #[Field\Type(PasswordType::class)]
    public string $password;

    #[Assert\Range(min: 18, max: 120)]
    public int $age;

    #[Assert\Url]
    public ?string $website = null;
}
```
