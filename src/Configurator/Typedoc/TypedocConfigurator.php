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

namespace Fasano\FormsBundle\Configurator\Typedoc;

use Fasano\FormsBundle\Field\FieldConfig;
use Fasano\Typedocs\TypeDoc;

interface TypedocConfigurator
{
    /** @return class-string<TypeDoc> */
    public function typedoc(): string;

    public function configure(FieldConfig $config, TypeDoc $attribute): void;
}