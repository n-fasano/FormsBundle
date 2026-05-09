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

use Fasano\FormsBundle\Configurator\Constraint\ConstraintConfigurator;
use Fasano\FormsBundle\Field\FieldConfig;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints;

class GreaterThanConfigurator implements ConstraintConfigurator
{
    public function constraint(): string
    {
        return Constraints\GreaterThan::class;
    }

    /** @param Constraints\GreaterThan $constraint */
    public function configure(FieldConfig $config, Constraint $constraint): void
    {
        $config->options['attr']['min'] = (int) $constraint->value + 1;
    }
}
