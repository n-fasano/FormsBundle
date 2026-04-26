<?php

declare(strict_types=1);

namespace Fasano\FormsBundle\Configurator\Constraint;

use Fasano\FormsBundle\Field\FieldConfig;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints;

class UlidConfigurator implements ConstraintConfigurator
{
    // Crockford base32 alphabet (26 characters)
    private const PATTERN = '[0-9A-HJKMNP-TV-Za-hjkmnp-tv-z]{26}';

    public function constraint(): string
    {
        return Constraints\Ulid::class;
    }

    public function configure(FieldConfig $config, Constraint $constraint): void
    {
        $config->options['attr']['pattern'] = self::PATTERN;
    }
}
