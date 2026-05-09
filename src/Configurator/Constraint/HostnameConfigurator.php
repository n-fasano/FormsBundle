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

class HostnameConfigurator implements ConstraintConfigurator
{
    private const WITH_TLD    = '[a-zA-Z0-9](?:[a-zA-Z0-9\-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9\-]{0,61}[a-zA-Z0-9])?)*\.[a-zA-Z]{2,}';
    private const WITHOUT_TLD = '[a-zA-Z0-9](?:[a-zA-Z0-9\-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9\-]{0,61}[a-zA-Z0-9])?)*';

    public function constraint(): string
    {
        return Constraints\Hostname::class;
    }

    /** @param Constraints\Hostname $constraint */
    public function configure(FieldConfig $config, Constraint $constraint): void
    {
        $config->options['attr']['pattern'] = $constraint->requireTld
            ? self::WITH_TLD
            : self::WITHOUT_TLD;
    }
}
