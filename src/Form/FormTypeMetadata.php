<?php

namespace Fasano\FormsBundle\Form;

final readonly class FormTypeMetadata
{
    public function __construct(
        public string $fqcn,
        public string $path,
    ) {}
}