<?php

namespace Fasano\FormsBundle;

use Fasano\FormsBundle\Form\FormTypeClassFactory;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Fasano\FormsBundle\Form\FormInterface as AnnotatedFormInterface;

class FormTypeFactory
{
    private const string CACHE_SUBDIR = 'formtypebundle/';

    public function __construct(
        private FormTypeClassFactory $formTypeClassFactory,
        private FormFactoryInterface $formFactory,
    ) {}

    /**
     * @template T
     * 
     * @param class-string<T> $fqcn
     * @param ?T $data
     * 
     * @return FormInterface&AnnotatedFormInterface
     */
    public function createForm(string $fqcn, mixed $data = null, array $options = []): FormInterface
    {
        $formTypeMetadata = $this->formTypeClassFactory->create($fqcn);

        return $this->formFactory->create($formTypeMetadata->fqcn, $data, $options);
    }

    public function createFormBuilder(string $fqcn, mixed $data = null, array $options = []): FormBuilderInterface
    {
        $formTypeMetadata = $this->formTypeClassFactory->create($fqcn);

        return $this->formFactory->createBuilder($formTypeMetadata->fqcn, $data, $options);
    }

    public function createFormType(string $fqcn): string
    {
        return $this->formTypeClassFactory->create($fqcn)->fqcn;
    }
}