<?php

namespace Fasano\FormsBundle\Tests\Integration\Cases;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WithOptGroupsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
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
                            'First Group' => 
                            array (
                              'A' => 'a',
                              'B' => 'b',
                            ),
                            'Second Group' => 
                            array (
                              'C' => 'c',
                              'D' => 'd',
                            ),
                          ),
                        ),
                    )
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            array (
              'data_class' => 'Fasano\\FormsBundle\\Tests\\Integration\\Cases\\WithOptGroups',
              'constraints' => 
              array (
                0 => new \Symfony\Component\Validator\Constraints\Valid(),
              ),
              'empty_data' => function (FormInterface $form) {
                try {
                    return new \Fasano\FormsBundle\Tests\Integration\Cases\WithOptGroups(...array_values(array_map(
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