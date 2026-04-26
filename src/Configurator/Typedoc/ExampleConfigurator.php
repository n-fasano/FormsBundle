<?php

declare(strict_types=1);

namespace Fasano\FormsBundle\Configurator\Typedoc;

use Fasano\FormsBundle\Configurator\Typedoc\TypedocConfigurator;
use Fasano\FormsBundle\Field\FieldConfig;
use Fasano\Typedocs;
use Fasano\Typedocs\TypeDoc;

class ExampleConfigurator implements TypedocConfigurator
{
    public function typedoc(): string
    {
        return TypeDocs\Example::class;
    }

    public function configure(FieldConfig $config, TypeDoc $attribute): void
    {
        /** @var TypeDocs\Example $attribute */
        $config->options['attr']['placeholder'] = $attribute->value;
    }
}