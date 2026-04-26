<?php

declare(strict_types=1);

namespace Fasano\FormsBundle\Configurator\Typedoc;

use Fasano\FormsBundle\Configurator\Typedoc\TypedocConfigurator;
use Fasano\FormsBundle\Field\FieldConfig;
use Fasano\Typedocs;
use Fasano\Typedocs\TypeDoc;

class NameConfigurator implements TypedocConfigurator
{
    public function typedoc(): string
    {
        return TypeDocs\Name::class;
    }

    public function configure(FieldConfig $config, TypeDoc $attribute): void
    {
        /** @var TypeDocs\Name $attribute */
        $config->options['label'] = $attribute->value;
    }
}