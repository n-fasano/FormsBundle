<?php

declare(strict_types=1);

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