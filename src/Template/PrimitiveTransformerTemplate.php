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

namespace Fasano\FormsBundle\Template;

final class PrimitiveTransformerTemplate
{
    public function __construct(
        private readonly string $propertyTypeName,
    ) {}

    public function render(): string
    {
        return <<<EOL
        new \Symfony\Component\Form\CallbackTransformer(
            fn (?{$this->propertyTypeName} \$primitive): mixed => \$primitive?->value ?? '',
            fn (mixed \$value): ?{$this->propertyTypeName} => 
                \Fasano\FormsBundle\Toolbox\Introspector::try('$this->propertyTypeName', \$value)
                ?? throw new \Symfony\Component\Form\Exception\TransformationFailedException(
                    \Fasano\FormsBundle\Toolbox\Introspector::error('$this->propertyTypeName', \$value)?->getMessage() ?? 'Something went wrong',
                ),
        )
        EOL;
    }
}