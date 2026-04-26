<?php

declare(strict_types=1);

namespace Fasano\FormsBundle\Configurator\BundleAttribute;

use Fasano\FormsBundle\Attribute\Field\Type;
use Fasano\FormsBundle\Attribute\FieldAttribute;
use Fasano\FormsBundle\Configurator\BundleAttribute\BundleAttributeConfigurator;
use Fasano\FormsBundle\Field\FieldConfig;
use Fasano\FormsBundle\Field\FieldContext;

class TypeConfigurator implements BundleAttributeConfigurator
{
    public function attribute(): string
    {
        return Type::class;
    }

    public function configure(FieldConfig $config, FieldAttribute $attribute, FieldContext $context): void
    {
        /** @var Type $attribute */
        $config->type = $attribute->value;
    }
}