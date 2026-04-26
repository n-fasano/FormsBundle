<?php

declare(strict_types=1);

namespace Fasano\FormsBundle\Configurator\Constraint;

use Fasano\FormsBundle\Field\FieldConfig;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints;

class IsTrueConfigurator implements ConstraintConfigurator
{
    public function constraint(): string
    {
        return Constraints\IsTrue::class;
    }

    public function configure(FieldConfig $config, Constraint $constraint): void
    {
        $config->type = CheckboxType::class;
    }
}
