<?php

declare(strict_types=1);

namespace Fasano\FormsBundle\Configurator\Constraint;

use Fasano\FormsBundle\Configurator\Constraint\ConstraintConfigurator;
use Fasano\FormsBundle\Field\FieldConfig;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints;

class RegexConfigurator implements ConstraintConfigurator
{
    public function constraint(): string
    {
        return Constraints\Regex::class;
    }

    /** @param Constraints\Regex $constraint */
    public function configure(FieldConfig $config, Constraint $constraint): void
    {
        $config->options['attr']['pattern'] = $constraint->getHtmlPattern();
    }
}
