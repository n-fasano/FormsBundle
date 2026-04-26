<?php

declare(strict_types=1);

namespace Fasano\FormsBundle\Configurator\Typedoc;

use Fasano\FormsBundle\Field\FieldConfig;
use Fasano\Typedocs\TypeDoc;

interface TypedocConfigurator
{
    /** @return class-string<TypeDoc> */
    public function typedoc(): string;

    public function configure(FieldConfig $config, TypeDoc $attribute): void;
}