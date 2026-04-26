<?php

declare(strict_types=1);

namespace Fasano\FormsBundle\Configurator\Typedoc;

use Fasano\FormsBundle\Configurator\Typedoc\TypedocConfigurator;
use Fasano\FormsBundle\Field\FieldConfig;
use Fasano\Typedocs;
use Fasano\Typedocs\TypeDoc;

class DescriptionConfigurator implements TypedocConfigurator
{
    public function typedoc(): string
    {
        return TypeDocs\Description::class;
    }

    public function configure(FieldConfig $config, TypeDoc $attribute): void
    {
        /** @var TypeDocs\Description $attribute */
        $config->options['help'] = $attribute->value;
    }
}