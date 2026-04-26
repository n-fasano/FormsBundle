<?php

declare(strict_types=1);

namespace Fasano\FormsBundle\Field;

interface FieldConfigurator
{
    public function configure(FieldContext $context): FieldConfig;
}