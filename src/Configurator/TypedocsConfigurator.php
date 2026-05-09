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

use Fasano\FormsBundle\Configurator\Typedoc\TypedocConfigurator;
use Fasano\FormsBundle\Field\FieldConfig;
use Fasano\FormsBundle\Field\FieldConfigurator;
use Fasano\FormsBundle\Field\FieldContext;
use Fasano\Typedocs\TypeDoc;

class TypedocsConfigurator implements FieldConfigurator
{
    /** @var array<class-string<TypeDoc>, TypedocConfigurator> */
    private array $configurators = [];

    /**
     * @param iterable<TypedocConfigurator> $configurators
     */
    public function __construct(iterable $configurators)
    {
        foreach ($configurators as $configurator) {
            $this->configurators[$configurator->typedoc()] = $configurator;
        }
    }

    public function configure(FieldContext $context): FieldConfig
    {
        $config = new FieldConfig();

        foreach ($context->attributes as $attribute) {
            if (!$attribute instanceof TypeDoc) {
                continue;
            }

            $configurator = $this->configurators[$attribute::class] ?? null;
            $configurator?->configure($config, $attribute);
        }

        return $config;
    }
}