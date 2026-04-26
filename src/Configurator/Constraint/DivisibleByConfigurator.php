<?php

declare(strict_types=1);

namespace Fasano\FormsBundle\Configurator\Constraint;

use Fasano\FormsBundle\Field\FieldConfig;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints;

class DivisibleByConfigurator implements ConstraintConfigurator
{
    public function constraint(): string
    {
        return Constraints\DivisibleBy::class;
    }

    /** @param Constraints\DivisibleBy $constraint */
    public function configure(FieldConfig $config, Constraint $constraint): void
    {
        $config->options['attr']['step'] = $constraint->value;
    }
}
