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

namespace Fasano\FormsBundle\Configurator\BundleAttribute;

use Fasano\FormsBundle\Attribute\Field\Type;
use Fasano\FormsBundle\Attribute\FieldAttribute;
use Fasano\FormsBundle\Configurator\BundleAttribute\BundleAttributeConfigurator;
use Fasano\FormsBundle\Field\FieldConfig;
use Fasano\FormsBundle\Field\FieldContext;

class TypeConfigurator implements BundleAttributeConfigurator
{
    public function attribute(): string
    {
        return Type::class;
    }

    public function configure(FieldConfig $config, FieldAttribute $attribute, FieldContext $context): void
    {
        /** @var Type $attribute */
        $config->type = $attribute->value;
    }
}