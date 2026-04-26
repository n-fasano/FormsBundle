<?php

declare(strict_types=1);

namespace Fasano\FormsBundle\Configurator\Constraint;

use Fasano\FormsBundle\Configurator\Constraint\ConstraintConfigurator;
use Fasano\FormsBundle\Field\FieldConfig;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints;

class LessThanConfigurator implements ConstraintConfigurator
{
    public function constraint(): string
    {
        return Constraints\LessThan::class;
    }

    /** @param Constraints\LessThan $constraint */
    public function configure(FieldConfig $config, Constraint $constraint): void
    {
        $config->options['attr']['max'] = ((int) $constraint->value) - 1;
    }
}
