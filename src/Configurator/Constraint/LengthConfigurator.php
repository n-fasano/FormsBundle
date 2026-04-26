<?php

declare(strict_types=1);

namespace Fasano\FormsBundle\Configurator\Constraint;

use Fasano\FormsBundle\Configurator\Constraint\ConstraintConfigurator;
use Fasano\FormsBundle\Field\FieldConfig;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints;

class LengthConfigurator implements ConstraintConfigurator
{
    public function constraint(): string
    {
        return Constraints\Length::class;
    }

    /** @param Constraints\Length $constraint */
    public function configure(FieldConfig $config, Constraint $constraint): void
    {
        if (null !== $constraint->min) {
            $config->options['attr']['minlength'] = (int) $constraint->min;
        }

        if (null !== $constraint->max) {
            $config->options['attr']['maxlength'] = (int) $constraint->max;
        }
    }
}