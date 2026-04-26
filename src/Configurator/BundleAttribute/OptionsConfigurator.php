<?php

declare(strict_types=1);

namespace Fasano\FormsBundle\Configurator\BundleAttribute;

use Fasano\FormsBundle\Attribute\Field\Options;
use Fasano\FormsBundle\Attribute\FieldAttribute;
use Fasano\FormsBundle\Configurator\BundleAttribute\BundleAttributeConfigurator;
use Fasano\FormsBundle\Field\FieldConfig;
use Fasano\FormsBundle\Field\FieldContext;

class OptionsConfigurator implements BundleAttributeConfigurator
{
    public function attribute(): string
    {
        return Options::class;
    }

    public function configure(FieldConfig $config, FieldAttribute $attribute, FieldContext $context): void
    {
        /** @var Options $attribute */
        $config->options = array_merge_recursive($config->options, $attribute->value);
    }
}