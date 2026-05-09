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
use Symfony\Component\Form\Extension\Core\Type\TimezoneType;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints;

class TimezoneConfigurator implements ConstraintConfigurator
{
    public function constraint(): string
    {
        return Constraints\Timezone::class;
    }

    public function configure(FieldConfig $config, Constraint $constraint): void
    {
        $config->type = TimezoneType::class;
    }
}
