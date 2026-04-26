<?php

declare(strict_types=1);

namespace Fasano\FormsBundle\Form;

use Fasano\FormsBundle\Field\FieldAnalyzer;
use Fasano\FormsBundle\Field\FieldConfigurator;
use Fasano\FormsBundle\FormTypeFactory;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class FormTypeGeneratorFactory
{
    /**
     * @param iterable<FieldConfigurator> $fieldConfigurators Tagged with 'forms.field_configurator'.
     *                                                         Lower priority = runs later = wins.
     *                                                         Built-ins: typesystem=30, constraints=20, typedocs=10.
     *                                                         User configurators default to 0 (override built-ins, but not explicit #[Field] attributes).
     *                                                         FormsBundleConfigurator is always last at -10.
     */
    public function __construct(
        protected UrlGeneratorInterface $urlGenerator,
        protected iterable $fieldConfigurators,
        protected FormTypeNamingStrategy $namingStrategy,
    ) {}

    public function create(FormTypeFactory $factory): FormTypeGenerator
    {
        $fieldAnalyzer = new FieldAnalyzer(
            iterator_to_array($this->fieldConfigurators, false),
            $factory,
        );

        return new FormTypeGenerator(
            $this->urlGenerator,
            $fieldAnalyzer,
            $this->namingStrategy,
        );
    }
}