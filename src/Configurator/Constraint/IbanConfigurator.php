<?php

declare(strict_types=1);

namespace Fasano\FormsBundle\Configurator\Constraint;

use Fasano\FormsBundle\Field\FieldConfig;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints;

class IbanConfigurator implements ConstraintConfigurator
{
    // 2-letter country code + 2-digit check number + 1–30 alphanumeric characters
    private const PATTERN = '[A-Za-z]{2}[0-9]{2}[A-Za-z0-9]{1,30}';

    public function constraint(): string
    {
        return Constraints\Iban::class;
    }

    public function configure(FieldConfig $config, Constraint $constraint): void
    {
        $config->options['attr']['pattern'] = self::PATTERN;
    }
}
