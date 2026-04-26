<?php

declare(strict_types=1);

namespace Fasano\FormsBundle\Attribute\Form;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
final readonly class Button
{
    public array $options;

    public function __construct(mixed ...$options)
    {
        $this->options = $options;
    }
}