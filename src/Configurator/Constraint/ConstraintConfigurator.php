<?php

declare(strict_types=1);

namespace Fasano\FormsBundle\Configurator\Constraint;

use Fasano\FormsBundle\Field\FieldConfig;
use Symfony\Component\Validator\Constraint;

interface ConstraintConfigurator
{
    /** @return class-string<Constraint> */
    public function constraint(): string;
    public function configure(FieldConfig $config, Constraint $constraint): void;
}