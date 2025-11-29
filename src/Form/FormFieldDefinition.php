<?php

namespace Fasano\FormsBundle\Form;

final readonly class FormFieldDefinition
{
    public function __construct(
        public string $name,
        public string $type,
        public array $options = [],
        public ?string $transformer = null,
    ) {}
}