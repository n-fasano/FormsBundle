<?php

declare(strict_types=1);

namespace Fasano\FormsBundle\Configurator\Constraint;

use Fasano\FormsBundle\Field\FieldConfig;
use Symfony\Component\Form\Extension\Core\Type\LanguageType;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints;

class LanguageConfigurator implements ConstraintConfigurator
{
    public function constraint(): string
    {
        return Constraints\Language::class;
    }

    public function configure(FieldConfig $config, Constraint $constraint): void
    {
        $config->type = LanguageType::class;
    }
}
