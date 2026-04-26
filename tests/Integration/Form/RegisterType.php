<?php

namespace Fasano\FormsBundle\Tests\Integration\Dto;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                $builder
                    ->create(
                        'email',
                        \Symfony\Component\Form\Extension\Core\Type\EmailType::class,
                        array (
                          'attr' => 
                          array (
                            'placeholder' => 'john.doe@example.com',
                          ),
                          'required' => true,
                          'label' => 'Email',
                          'help' => 'An email address',
                        ),
                    )
                    ->addModelTransformer(
                        new \Symfony\Component\Form\CallbackTransformer(
                            fn (?\Fasano\FormsBundle\Tests\Integration\Dto\Email $primitive): mixed => $primitive?->value ?? '',
                            fn (mixed $value): ?\Fasano\FormsBundle\Tests\Integration\Dto\Email => 
                                \Fasano\FormsBundle\Toolbox\Introspector::try('\Fasano\FormsBundle\Tests\Integration\Dto\Email', $value)
                                ?? throw new \Symfony\Component\Form\Exception\TransformationFailedException(
                                    \Fasano\FormsBundle\Toolbox\Introspector::error('\Fasano\FormsBundle\Tests\Integration\Dto\Email', $value)?->getMessage() ?? 'Something went wrong',
                                ),
                        )
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
                            'placeholder' => '$0O7b#%FWA#Nezxv',
                          ),
                          'required' => true,
                          'label' => 'Password',
                          'help' => 'A password with at least 1 uppercase, 1 lowercase, and 1 special character.',
                        ),
                    )
                    ->addModelTransformer(
                        new \Symfony\Component\Form\CallbackTransformer(
                            fn (?\Fasano\FormsBundle\Tests\Integration\Dto\Password $primitive): mixed => $primitive?->value ?? '',
                            fn (mixed $value): ?\Fasano\FormsBundle\Tests\Integration\Dto\Password => 
                                \Fasano\FormsBundle\Toolbox\Introspector::try('\Fasano\FormsBundle\Tests\Integration\Dto\Password', $value)
                                ?? throw new \Symfony\Component\Form\Exception\TransformationFailedException(
                                    \Fasano\FormsBundle\Toolbox\Introspector::error('\Fasano\FormsBundle\Tests\Integration\Dto\Password', $value)?->getMessage() ?? 'Something went wrong',
                                ),
                        )
                    )
            )
            ->add(
                'submit',
                \Symfony\Component\Form\Extension\Core\Type\SubmitType::class,
                array (
                  'label' => 'Register',
                ),
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            array (
              'action' => '/example',
              'data_class' => 'Fasano\\FormsBundle\\Tests\\Integration\\Dto\\Register',
              'constraints' => 
              array (
                0 => new \Symfony\Component\Validator\Constraints\Valid(),
              ),
              'empty_data' => function (FormInterface $form) {
                try {
                    return new \Fasano\FormsBundle\Tests\Integration\Dto\Register(...array_values(array_map(
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