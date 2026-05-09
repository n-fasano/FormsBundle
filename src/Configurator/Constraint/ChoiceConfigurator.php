<?php

declare(strict_types=1);

/*
 * This file is part of the FormsBundle package.
 *
 * (c) Nicolas Fasano <fasano.nm@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
