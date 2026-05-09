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

interface ConstraintConfigurator
{
    /** @return class-string<Constraint> */
    public function constraint(): string;
    public function configure(FieldConfig $config, Constraint $constraint): void;
}