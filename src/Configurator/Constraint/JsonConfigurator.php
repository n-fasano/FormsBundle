<?php

declare(strict_types=1);

namespace Fasano\FormsBundle\Configurator\Constraint;

use Fasano\FormsBundle\Field\FieldConfig;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints;

class JsonConfigurator implements ConstraintConfigurator
{
    public function constraint(): string
    {
        return Constraints\Json::class;
    }

    public function configure(FieldConfig $config, Constraint $constraint): void
    {
        $config->type = TextareaType::class;
    }
}
