<?php

declare(strict_types=1);

namespace Fasano\FormsBundle\Field;

use Fasano\FormsBundle\Toolbox\ArrayMerger;

final class FieldConfig
{
    public function __construct(
        public ?string $name = null,
        public ?string $type = null,
        public array $options = ['attr' => []],
    ) {}

    public function override(self $other): static
    {
        if ($other->name !== null) {
            $this->name = $other->name;
        }
        if ($other->type !== null) {
            $this->type = $other->type;
        }
        $this->options = array_replace_recursive($this->options, $other->options);

        return $this;
    }
}