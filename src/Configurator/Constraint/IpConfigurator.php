<?php

declare(strict_types=1);

namespace Fasano\FormsBundle\Configurator\Constraint;

use Fasano\FormsBundle\Field\FieldConfig;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints;

class IpConfigurator implements ConstraintConfigurator
{
    protected const V4 = '\d{1,3}(\.\d{1,3}){3}';
    protected const V6 = '[0-9a-fA-F:]{2,39}';

    public function constraint(): string
    {
        return Constraints\Ip::class;
    }

    /** @param Constraints\Ip $constraint */
    public function configure(FieldConfig $config, Constraint $constraint): void
    {
        $config->options['attr']['pattern'] = $this->patternFor($constraint->version);
    }

    protected function patternFor(string $version): string
    {
        return match (true) {
            str_starts_with($version, '4')   => static::V4,
            str_starts_with($version, '6')   => static::V6,
            default                          => static::V4 . '|' . static::V6,
        };
    }
}
