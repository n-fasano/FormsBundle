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

use Fasano\FormsBundle\Attribute\Field\EntryType;
use Fasano\FormsBundle\Attribute\FieldAttribute;
use Fasano\FormsBundle\Field\FieldConfig;
use Fasano\FormsBundle\Field\FieldContext;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormTypeInterface;

class EntryTypeConfigurator implements BundleAttributeConfigurator
{
    public function attribute(): string
    {
        return EntryType::class;
    }

    public function configure(FieldConfig $config, FieldAttribute $attribute, FieldContext $context): void
    {
        /** @var EntryType $attribute */
        $config->type = CollectionType::class;
        $config->options['entry_type'] = is_a($attribute->class, FormTypeInterface::class, true)
            ? $attribute->class
            : $context->factory->createFormType($attribute->class);
    }
}
