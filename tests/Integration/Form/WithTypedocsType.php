<?php

namespace Fasano\FormsBundle\Tests\Integration\Cases;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WithTypedocsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                $builder
                    ->create(
                        'ip',
                        \Symfony\Component\Form\Extension\Core\Type\TextType::class,
                        array (
                          'attr' => 
                          array (
                            'placeholder' => '0.0.0.0',
                          ),
                          'required' => true,
                          'label' => 'IP',
                          'help' => 'An IPv4 address',
                        ),
                    )
                    ->addModelTransformer(
                        new \Symfony\Component\Form\CallbackTransformer(
                            fn (?\Fasano\FormsBundle\Tests\Integration\Cases\IpAddress $primitive): mixed => $primitive?->value ?? '',
                            fn (mixed $value): ?\Fasano\FormsBundle\Tests\Integration\Cases\IpAddress => 
                                \Fasano\FormsBundle\Toolbox\Introspector::try('\Fasano\FormsBundle\Tests\Integration\Cases\IpAddress', $value)
                                ?? throw new \Symfony\Component\Form\Exception\TransformationFailedException(
                                    \Fasano\FormsBundle\Toolbox\Introspector::error('\Fasano\FormsBundle\Tests\Integration\Cases\IpAddress', $value)?->getMessage() ?? 'Something went wrong',
                                ),
                        )
                    )
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            array (
              'data_class' => 'Fasano\\FormsBundle\\Tests\\Integration\\Cases\\WithTypedocs',
              'constraints' => 
              array (
                0 => new \Symfony\Component\Validator\Constraints\Valid(),
              ),
              'empty_data' => function (FormInterface $form) {
                try {
                    return new \Fasano\FormsBundle\Tests\Integration\Cases\WithTypedocs(...array_values(array_map(
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