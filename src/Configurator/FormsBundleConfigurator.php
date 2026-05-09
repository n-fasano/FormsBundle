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

namespace Fasano\FormsBundle\Configurator;

use Fasano\FormsBundle\Attribute\FieldAttribute;
use Fasano\FormsBundle\Configurator\BundleAttribute\BundleAttributeConfigurator;
use Fasano\FormsBundle\Field\FieldConfig;
use Fasano\FormsBundle\Field\FieldConfigurator;
use Fasano\FormsBundle\Field\FieldContext;

class FormsBundleConfigurator implements FieldConfigurator
{
    /** @var array<class-string<FieldAttribute>, BundleAttributeConfigurator> */
    private array $configurators = [];

    /**
     * @param iterable<BundleAttributeConfigurator> $configurators
     */
    public function __construct(iterable $configurators)
    {
        foreach ($configurators as $configurator) {
            $this->configurators[$configurator->attribute()] = $configurator;
        }
    }

    public function configure(FieldContext $context): FieldConfig
    {
        $config = new FieldConfig();

        foreach ($context->attributes as $attribute) {
            if (!$attribute instanceof FieldAttribute) {
                continue;
            }

            $configurator = $this->configurators[$attribute::class] ?? null;
            $configurator?->configure($config, $attribute, $context);
        }

        return $config;
    }
}