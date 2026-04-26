<?php

namespace Fasano\FormsBundle\Tests\Integration\Cases;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WithConstraintsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                $builder
                    ->create(
                        'url',
                        \Symfony\Component\Form\Extension\Core\Type\UrlType::class,
                        array (
                          'attr' => 
                          array (
                          ),
                          'required' => true,
                          'label' => 'Url',
                        ),
                    )
            )
            ->add(
                $builder
                    ->create(
                        'email',
                        \Symfony\Component\Form\Extension\Core\Type\EmailType::class,
                        array (
                          'attr' => 
                          array (
                          ),
                          'required' => true,
                          'label' => 'Email',
                        ),
                    )
            )
            ->add(
                $builder
                    ->create(
                        'choice',
                        \Symfony\Component\Form\Extension\Core\Type\ChoiceType::class,
                        array (
                          'attr' => 
                          array (
                          ),
                          'required' => true,
                          'label' => 'Choice',
                          'choices' => 
                          array (
                            'foo' => 'foo',
                            'bar' => 'bar',
                          ),
                        ),
                    )
            )
            ->add(
                $builder
                    ->create(
                        'dateTime',
                        \Symfony\Component\Form\Extension\Core\Type\DateTimeType::class,
                        array (
                          'attr' => 
                          array (
                          ),
                          'required' => true,
                          'label' => 'Date Time',
                        ),
                    )
            )
            ->add(
                $builder
                    ->create(
                        'date',
                        \Symfony\Component\Form\Extension\Core\Type\DateType::class,
                        array (
                          'attr' => 
                          array (
                          ),
                          'required' => true,
                          'label' => 'Date',
                        ),
                    )
            )
            ->add(
                $builder
                    ->create(
                        'time',
                        \Symfony\Component\Form\Extension\Core\Type\TimeType::class,
                        array (
                          'attr' => 
                          array (
                          ),
                          'required' => true,
                          'label' => 'Time',
                        ),
                    )
            )
            ->add(
                $builder
                    ->create(
                        'notBlank',
                        \Symfony\Component\Form\Extension\Core\Type\TextType::class,
                        array (
                          'attr' => 
                          array (
                          ),
                          'required' => true,
                          'label' => 'Not Blank',
                        ),
                    )
            )
            ->add(
                $builder
                    ->create(
                        'length',
                        \Symfony\Component\Form\Extension\Core\Type\TextType::class,
                        array (
                          'attr' => 
                          array (
                            'minlength' => 5,
                            'maxlength' => 10,
                          ),
                          'required' => true,
                          'label' => 'Length',
                        ),
                    )
            )
            ->add(
                $builder
                    ->create(
                        'regex',
                        \Symfony\Component\Form\Extension\Core\Type\TextType::class,
                        array (
                          'attr' => 
                          array (
                            'pattern' => '.*\\d{6}.*',
                          ),
                          'required' => true,
                          'label' => 'Regex',
                        ),
                    )
            )
            ->add(
                $builder
                    ->create(
                        'range',
                        \Symfony\Component\Form\Extension\Core\Type\IntegerType::class,
                        array (
                          'attr' => 
                          array (
                            'min' => 3,
                            'max' => 12,
                          ),
                          'required' => true,
                          'label' => 'Range',
                        ),
                    )
            )
            ->add(
                $builder
                    ->create(
                        'positive',
                        \Symfony\Component\Form\Extension\Core\Type\IntegerType::class,
                        array (
                          'attr' => 
                          array (
                            'min' => 1,
                          ),
                          'required' => true,
                          'label' => 'Positive',
                        ),
                    )
            )
            ->add(
                $builder
                    ->create(
                        'positiveOrZero',
                        \Symfony\Component\Form\Extension\Core\Type\IntegerType::class,
                        array (
                          'attr' => 
                          array (
                            'min' => 0,
                          ),
                          'required' => true,
                          'label' => 'Positive Or Zero',
                        ),
                    )
            )
            ->add(
                $builder
                    ->create(
                        'negative',
                        \Symfony\Component\Form\Extension\Core\Type\IntegerType::class,
                        array (
                          'attr' => 
                          array (
                            'max' => -1,
                          ),
                          'required' => true,
                          'label' => 'Negative',
                        ),
                    )
            )
            ->add(
                $builder
                    ->create(
                        'negativeOrZero',
                        \Symfony\Component\Form\Extension\Core\Type\IntegerType::class,
                        array (
                          'attr' => 
                          array (
                            'max' => 0,
                          ),
                          'required' => true,
                          'label' => 'Negative Or Zero',
                        ),
                    )
            )
            ->add(
                $builder
                    ->create(
                        'lessThan',
                        \Symfony\Component\Form\Extension\Core\Type\IntegerType::class,
                        array (
                          'attr' => 
                          array (
                            'max' => 2,
                          ),
                          'required' => true,
                          'label' => 'Less Than',
                        ),
                    )
            )
            ->add(
                $builder
                    ->create(
                        'lessThanOrEqual',
                        \Symfony\Component\Form\Extension\Core\Type\IntegerType::class,
                        array (
                          'attr' => 
                          array (
                            'max' => 3,
                          ),
                          'required' => true,
                          'label' => 'Less Than Or Equal',
                        ),
                    )
            )
            ->add(
                $builder
                    ->create(
                        'greaterThan',
                        \Symfony\Component\Form\Extension\Core\Type\IntegerType::class,
                        array (
                          'attr' => 
                          array (
                            'min' => 4,
                          ),
                          'required' => true,
                          'label' => 'Greater Than',
                        ),
                    )
            )
            ->add(
                $builder
                    ->create(
                        'greaterThanOrEqual',
                        \Symfony\Component\Form\Extension\Core\Type\IntegerType::class,
                        array (
                          'attr' => 
                          array (
                            'min' => 3,
                          ),
                          'required' => true,
                          'label' => 'Greater Than Or Equal',
                        ),
                    )
            )
            ->add(
                $builder
                    ->create(
                        'divisibleBy',
                        \Symfony\Component\Form\Extension\Core\Type\IntegerType::class,
                        array (
                          'attr' => 
                          array (
                            'step' => 5,
                          ),
                          'required' => true,
                          'label' => 'Divisible By',
                        ),
                    )
            )
            ->add(
                $builder
                    ->create(
                        'notNull',
                        \Symfony\Component\Form\Extension\Core\Type\TextType::class,
                        array (
                          'attr' => 
                          array (
                          ),
                          'required' => true,
                          'label' => 'Not Null',
                        ),
                    )
            )
            ->add(
                $builder
                    ->create(
                        'isTrue',
                        \Symfony\Component\Form\Extension\Core\Type\CheckboxType::class,
                        array (
                          'attr' => 
                          array (
                          ),
                          'required' => true,
                          'label' => 'Is True',
                        ),
                    )
            )
            ->add(
                $builder
                    ->create(
                        'uuid',
                        \Symfony\Component\Form\Extension\Core\Type\TextType::class,
                        array (
                          'attr' => 
                          array (
                            'pattern' => '[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}',
                          ),
                          'required' => true,
                          'label' => 'Uuid',
                        ),
                    )
            )
            ->add(
                $builder
                    ->create(
                        'bic',
                        \Symfony\Component\Form\Extension\Core\Type\TextType::class,
                        array (
                          'attr' => 
                          array (
                            'pattern' => '[A-Za-z]{4}[A-Za-z]{2}[A-Za-z0-9]{2}(?:[A-Za-z0-9]{3})?',
                          ),
                          'required' => true,
                          'label' => 'Bic',
                        ),
                    )
            )
            ->add(
                $builder
                    ->create(
                        'iban',
                        \Symfony\Component\Form\Extension\Core\Type\TextType::class,
                        array (
                          'attr' => 
                          array (
                            'pattern' => '[A-Za-z]{2}[0-9]{2}[A-Za-z0-9]{1,30}',
                          ),
                          'required' => true,
                          'label' => 'Iban',
                        ),
                    )
            )
            ->add(
                $builder
                    ->create(
                        'isbn',
                        \Symfony\Component\Form\Extension\Core\Type\TextType::class,
                        array (
                          'attr' => 
                          array (
                            'pattern' => '[0-9]{9}[0-9X]|97[89][0-9]{10}',
                          ),
                          'required' => true,
                          'label' => 'Isbn',
                        ),
                    )
            )
            ->add(
                $builder
                    ->create(
                        'isbn10',
                        \Symfony\Component\Form\Extension\Core\Type\TextType::class,
                        array (
                          'attr' => 
                          array (
                            'pattern' => '[0-9]{9}[0-9X]',
                          ),
                          'required' => true,
                          'label' => 'Isbn10',
                        ),
                    )
            )
            ->add(
                $builder
                    ->create(
                        'isbn13',
                        \Symfony\Component\Form\Extension\Core\Type\TextType::class,
                        array (
                          'attr' => 
                          array (
                            'pattern' => '97[89][0-9]{10}',
                          ),
                          'required' => true,
                          'label' => 'Isbn13',
                        ),
                    )
            )
            ->add(
                $builder
                    ->create(
                        'card',
                        \Symfony\Component\Form\Extension\Core\Type\TextType::class,
                        array (
                          'attr' => 
                          array (
                            'pattern' => '4(?:[0-9]{12}|[0-9]{15}|[0-9]{18})|5[1-5][0-9]{14}|2(?:22[1-9][0-9]{12}|2[3-9][0-9]{13}|[3-6][0-9]{14}|7[0-1][0-9]{13}|720[0-9]{12})',
                          ),
                          'required' => true,
                          'label' => 'Card',
                        ),
                    )
            )
            ->add(
                $builder
                    ->create(
                        'password',
                        \Symfony\Component\Form\Extension\Core\Type\PasswordType::class,
                        array (
                          'attr' => 
                          array (
                          ),
                          'required' => true,
                          'label' => 'Password',
                        ),
                    )
            )
            ->add(
                $builder
                    ->create(
                        'ipv4',
                        \Symfony\Component\Form\Extension\Core\Type\TextType::class,
                        array (
                          'attr' => 
                          array (
                            'pattern' => '\\d{1,3}(\\.\\d{1,3}){3}',
                          ),
                          'required' => true,
                          'label' => 'Ipv4',
                        ),
                    )
            )
            ->add(
                $builder
                    ->create(
                        'ip',
                        \Symfony\Component\Form\Extension\Core\Type\TextType::class,
                        array (
                          'attr' => 
                          array (
                            'pattern' => '\\d{1,3}(\\.\\d{1,3}){3}|[0-9a-fA-F:]{2,39}',
                          ),
                          'required' => true,
                          'label' => 'Ip',
                        ),
                    )
            )
            ->add(
                $builder
                    ->create(
                        'cidr',
                        \Symfony\Component\Form\Extension\Core\Type\TextType::class,
                        array (
                          'attr' => 
                          array (
                            'pattern' => '(?:\\d{1,3}(\\.\\d{1,3}){3})/\\d{1,2}|(?:[0-9a-fA-F:]{2,39})/\\d{1,3}',
                          ),
                          'required' => true,
                          'label' => 'Cidr',
                        ),
                    )
            )
            ->add(
                $builder
                    ->create(
                        'hostname',
                        \Symfony\Component\Form\Extension\Core\Type\TextType::class,
                        array (
                          'attr' => 
                          array (
                            'pattern' => '[a-zA-Z0-9](?:[a-zA-Z0-9\\-]{0,61}[a-zA-Z0-9])?(?:\\.[a-zA-Z0-9](?:[a-zA-Z0-9\\-]{0,61}[a-zA-Z0-9])?)*\\.[a-zA-Z]{2,}',
                          ),
                          'required' => true,
                          'label' => 'Hostname',
                        ),
                    )
            )
            ->add(
                $builder
                    ->create(
                        'macAddress',
                        \Symfony\Component\Form\Extension\Core\Type\TextType::class,
                        array (
                          'attr' => 
                          array (
                            'pattern' => '[0-9a-fA-F]{2}(?:[:\\-][0-9a-fA-F]{2}){5}',
                          ),
                          'required' => true,
                          'label' => 'Mac Address',
                        ),
                    )
            )
            ->add(
                $builder
                    ->create(
                        'ulid',
                        \Symfony\Component\Form\Extension\Core\Type\TextType::class,
                        array (
                          'attr' => 
                          array (
                            'pattern' => '[0-9A-HJKMNP-TV-Za-hjkmnp-tv-z]{26}',
                          ),
                          'required' => true,
                          'label' => 'Ulid',
                        ),
                    )
            )
            ->add(
                $builder
                    ->create(
                        'json',
                        \Symfony\Component\Form\Extension\Core\Type\TextareaType::class,
                        array (
                          'attr' => 
                          array (
                          ),
                          'required' => true,
                          'label' => 'Json',
                        ),
                    )
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            array (
              'data_class' => 'Fasano\\FormsBundle\\Tests\\Integration\\Cases\\WithConstraints',
              'constraints' => 
              array (
                0 => new \Symfony\Component\Validator\Constraints\Valid(),
              ),
              'empty_data' => function (FormInterface $form) {
                try {
                    return new \Fasano\FormsBundle\Tests\Integration\Cases\WithConstraints(...array_values(array_map(
                        fn (FormInterface $child) => $child->getData(),
                        $form->all(),
                    )));
                } catch (\InvalidArgumentException $e) {
                    throw new \Symfony\Component\Form\Exception\TransformationFailedException(
                        message: $e->getMessage(),
                        previous: $e,
                    );
                } catch (\Throwable $e) {
                    throw new \Symfony\Component\Form\Exception\TransformationFailedException(
                        message: 'Invalid form',
                        previous: $e,
                    );
                }
            },
            )
        );
    }
}