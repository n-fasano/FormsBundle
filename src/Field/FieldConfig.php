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