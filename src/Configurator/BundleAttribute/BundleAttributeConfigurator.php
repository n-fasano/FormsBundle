<?php

declare(strict_types=1);

namespace Fasano\FormsBundle\Configurator\BundleAttribute;

use Fasano\FormsBundle\Attribute\FieldAttribute;
use Fasano\FormsBundle\Field\FieldConfig;
use Fasano\FormsBundle\Field\FieldContext;

interface BundleAttributeConfigurator
{
    /** @return class-string<FieldAttribute> */
    public function attribute(): string;

    public function configure(FieldConfig $config, FieldAttribute $attribute, FieldContext $context): void;
}