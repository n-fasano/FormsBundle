<?php

declare(strict_types=1);

/*
 * This file is part of the FormsBundle package.
 *
 * (c) Nicolas Fasano <fasano.nm@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
