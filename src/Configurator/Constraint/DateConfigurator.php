<?php

declare(strict_types=1);

namespace Fasano\FormsBundle\Configurator\Constraint;

use Fasano\FormsBundle\Configurator\Constraint\ConstraintConfigurator;
use Fasano\FormsBundle\Field\FieldConfig;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints;

class DateConfigurator implements ConstraintConfigurator
{
    public function constraint(): string
    {
        return Constraints\Date::class;
    }

    public function configure(FieldConfig $config, Constraint $constraint): void
    {
        $config->type = DateType::class;
    }
}
