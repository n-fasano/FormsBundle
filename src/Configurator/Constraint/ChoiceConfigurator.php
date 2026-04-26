<?php

declare(strict_types=1);

namespace Fasano\FormsBundle\Configurator\Constraint;

use Fasano\FormsBundle\Configurator\Constraint\ConstraintConfigurator;
use Fasano\FormsBundle\Field\FieldConfig;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints;

class ChoiceConfigurator implements ConstraintConfigurator
{
    public function constraint(): string
    {
        return Constraints\Choice::class;
    }

    /** @param Constraints\Choice $constraint */
    public function configure(FieldConfig $config, Constraint $constraint): void
    {
        $config->type = ChoiceType::class;

        if (null !== $constraint->choices) {
            $config->options['choices'] = array_combine($constraint->choices, $constraint->choices);
        }

        if ($constraint->multiple) {
            $config->options['multiple'] = true;
        }
    }
}
