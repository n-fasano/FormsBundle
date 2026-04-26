<?php

declare(strict_types=1);

namespace Fasano\FormsBundle\Configurator\Constraint;

use Fasano\FormsBundle\Field\FieldConfig;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints;

class IsbnConfigurator implements ConstraintConfigurator
{
    private const ISBN_10 = '[0-9]{9}[0-9X]';
    private const ISBN_13 = '97[89][0-9]{10}';

    public function constraint(): string
    {
        return Constraints\Isbn::class;
    }

    /** @param Constraints\Isbn $constraint */
    public function configure(FieldConfig $config, Constraint $constraint): void
    {
        $config->options['attr']['pattern'] = match ($constraint->type) {
            Constraints\Isbn::ISBN_10 => self::ISBN_10,
            Constraints\Isbn::ISBN_13 => self::ISBN_13,
            default                   => self::ISBN_10 . '|' . self::ISBN_13,
        };
    }
}
