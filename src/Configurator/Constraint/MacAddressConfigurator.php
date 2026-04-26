<?php

declare(strict_types=1);

namespace Fasano\FormsBundle\Configurator\Constraint;

use Fasano\FormsBundle\Field\FieldConfig;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints;

class MacAddressConfigurator implements ConstraintConfigurator
{
    private const PATTERN = '[0-9a-fA-F]{2}(?:[:\-][0-9a-fA-F]{2}){5}';

    public function constraint(): string
    {
        return Constraints\MacAddress::class;
    }

    public function configure(FieldConfig $config, Constraint $constraint): void
    {
        $config->options['attr']['pattern'] = self::PATTERN;
    }
}
