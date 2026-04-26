<?php

declare(strict_types=1);

namespace Fasano\FormsBundle\Configurator\Constraint;

use Fasano\FormsBundle\Field\FieldConfig;
use Symfony\Component\Form\Extension\Core\Type\CurrencyType;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints;

class CurrencyConfigurator implements ConstraintConfigurator
{
    public function constraint(): string
    {
        return Constraints\Currency::class;
    }

    public function configure(FieldConfig $config, Constraint $constraint): void
    {
        $config->type = CurrencyType::class;
    }
}
