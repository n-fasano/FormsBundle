<?php

namespace Fasano\FormsBundle\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
final readonly class FormConfig
{
    public function __construct(
        public ?string $submitLabel = null,
        public array $validationGroups = ['Default'],
    ) {}
}