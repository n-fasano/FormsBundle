<?php

declare(strict_types=1);

namespace Fasano\FormsBundle\Configurator\Constraint;

use Fasano\FormsBundle\Field\FieldConfig;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints;

class NotCompromisedPasswordConfigurator implements ConstraintConfigurator
{
    public function constraint(): string
    {
        return Constraints\NotCompromisedPassword::class;
    }

    public function configure(FieldConfig $config, Constraint $constraint): void
    {
        $config->type = PasswordType::class;
    }
}
