<?php

declare(strict_types=1);

namespace Fasano\FormsBundle\Configurator\BundleAttribute;

use Fasano\FormsBundle\Attribute\Field\Attributes;
use Fasano\FormsBundle\Attribute\FieldAttribute;
use Fasano\FormsBundle\Configurator\BundleAttribute\BundleAttributeConfigurator;
use Fasano\FormsBundle\Field\FieldConfig;
use Fasano\FormsBundle\Field\FieldContext;

class AttributesConfigurator implements BundleAttributeConfigurator
{
    public function attribute(): string
    {
        return Attributes::class;
    }

    public function configure(FieldConfig $config, FieldAttribute $attribute, FieldContext $context): void
    {
        /** @var Attributes $attribute */
        $config->options['attr'] ??= [];
        $config->options['attr'] = array_merge($config->options['attr'], $attribute->value);
    }
}