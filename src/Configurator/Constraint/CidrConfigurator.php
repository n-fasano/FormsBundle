<?php

declare(strict_types=1);

namespace Fasano\FormsBundle\Configurator\Constraint;

use Fasano\FormsBundle\Field\FieldConfig;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints;

class CidrConfigurator extends IpConfigurator
{
    public function constraint(): string
    {
        return Constraints\Cidr::class;
    }

    /** @param Constraints\Cidr $constraint */
    public function configure(FieldConfig $config, Constraint $constraint): void
    {
        $ipPattern = $this->patternFor($constraint->version);

        $config->options['attr']['pattern'] = str_contains($ipPattern, '|')
            ? '(?:' . static::V4 . ')/\d{1,2}|(?:' . static::V6 . ')/\d{1,3}'
            : $ipPattern . '/\d{1,' . ($constraint->netmaskMax ?? 128) . '}';
    }
}
