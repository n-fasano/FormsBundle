<?php

declare(strict_types=1);

namespace Fasano\FormsBundle\Field;

final readonly class FieldDefinition
{
    public function __construct(
        public string $name,
        public string $type,
        public array $options = [],
        public ?string $transformer = null,
    ) {}
}