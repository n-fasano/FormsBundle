<?php

declare(strict_types=1);

namespace Fasano\FormsBundle\Configurator;

use Fasano\FormsBundle\Configurator\Constraint\ConstraintConfigurator;
use Fasano\FormsBundle\Field\FieldConfig;
use Fasano\FormsBundle\Field\FieldConfigurator;
use Fasano\FormsBundle\Field\FieldContext;
use Symfony\Component\Validator\Constraint;

class ConstraintsConfigurator implements FieldConfigurator
{
    /** @var array<class-string<Constraint>, ConstraintConfigurator> */
    private array $configurators = [];

    /**
     * @param iterable<ConstraintConfigurator> $constraintConfigurators
     */
    public function __construct(iterable $constraintConfigurators)
    {
        foreach ($constraintConfigurators as $configurator) {
            $this->configurators[$configurator->constraint()] = $configurator;
        }
    }

    public function configure(FieldContext $context): FieldConfig
    {
        $config = new FieldConfig();

        foreach ($context->attributes as $attribute) {
            if (!$attribute instanceof Constraint) {
                continue;
            }

            $configurator = $this->configurators[$attribute::class] ?? null;
            $configurator?->configure($config, $attribute);
        }

        return $config;
    }
}