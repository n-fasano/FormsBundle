<?php

namespace Fasano\FormsBundle\Tests\Integration\Cases;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DefaultTypeResolutionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                $builder
                    ->create(
                        'string',
                        \Symfony\Component\Form\Extension\Core\Type\TextType::class,
                        array (
                          'attr' => 
                          array (
                          ),
                          'required' => true,
                          'label' => 'String',
                        ),
                    )
            )
            ->add(
                $builder
                    ->create(
                        'integer',
                        \Symfony\Component\Form\Extension\Core\Type\IntegerType::class,
                        array (
                          'attr' => 
                          array (
                          ),
                          'required' => true,
                          'label' => 'Integer',
                        ),
                    )
            )
            ->add(
                $builder
                    ->create(
                        'float',
                        \Symfony\Component\Form\Extension\Core\Type\NumberType::class,
                        array (
                          'attr' => 
                          array (
                          ),
                          'required' => true,
                          'label' => 'Float',
                        ),
                    )
            )
            ->add(
                $builder
                    ->create(
                        'boolean',
                        \Symfony\Component\Form\Extension\Core\Type\CheckboxType::class,
                        array (
                          'attr' => 
                          array (
                          ),
                          'required' => true,
                          'label' => 'Boolean',
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
                          'input' => 'datetime',
                        ),
                    )
            )
            ->add(
                $builder
                    ->create(
                        'dateTimeImmutable',
                        \Symfony\Component\Form\Extension\Core\Type\DateTimeType::class,
                        array (
                          'attr' => 
                          array (
                          ),
                          'required' => true,
                          'label' => 'Date Time Immutable',
                          'input' => 'datetime_immutable',
                        ),
                    )
            )
            ->add(
                $builder
                    ->create(
                        'array',
                        \Symfony\Component\Form\Extension\Core\Type\CollectionType::class,
                        array (
                          'attr' => 
                          array (
                          ),
                          'required' => true,
                          'label' => 'Array',
                        ),
                    )
            )
            ->add(
                $builder
                    ->create(
                        'defaultsToText',
                        \Symfony\Component\Form\Extension\Core\Type\TextType::class,
                        array (
                          'attr' => 
                          array (
                          ),
                          'required' => false,
                          'label' => 'Defaults To Text',
                        ),
                    )
            )
            ->add(
                $builder
                    ->create(
                        'nullable',
                        \Symfony\Component\Form\Extension\Core\Type\TextType::class,
                        array (
                          'attr' => 
                          array (
                          ),
                          'required' => false,
                          'label' => 'Nullable',
                        ),
                    )
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            array (
              'data_class' => 'Fasano\\FormsBundle\\Tests\\Integration\\Cases\\DefaultTypeResolution',
              'constraints' => 
              array (
                0 => new \Symfony\Component\Validator\Constraints\Valid(),
              ),
              'empty_data' => function (FormInterface $form) {
                try {
                    return new \Fasano\FormsBundle\Tests\Integration\Cases\DefaultTypeResolution(...array_values(array_map(
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