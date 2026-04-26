<?php

declare(strict_types=1);

namespace Fasano\FormsBundle\Configurator\Constraint;

use Fasano\FormsBundle\Configurator\Constraint\ConstraintConfigurator;
use Fasano\FormsBundle\Field\FieldConfig;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints;

class UrlConfigurator implements ConstraintConfigurator
{
    public function constraint(): string
    {
        return Constraints\Url::class;
    }

    public function configure(FieldConfig $config, Constraint $constraint): void
    {
        $config->type = UrlType::class;
    }
}