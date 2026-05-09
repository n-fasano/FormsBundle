<?php

declare(strict_types=1);

/*
 * This file is part of the FormsBundle package.
 *
 * (c) Nicolas Fasano <fasano.nm@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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