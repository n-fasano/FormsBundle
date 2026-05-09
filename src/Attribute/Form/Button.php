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