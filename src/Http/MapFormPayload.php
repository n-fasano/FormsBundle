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

namespace Fasano\FormsBundle\Http;

use Symfony\Component\HttpKernel\Attribute\ValueResolver;

#[\Attribute(\Attribute::TARGET_PARAMETER)]
class MapFormPayload extends ValueResolver
{
    /**
     * @param class-string $formDtoFqcn
     */
    public function __construct(
        public string $formDtoFqcn,
        bool $disabled = false,
    ) {
        parent::__construct(FormValueResolver::class, $disabled);
    }
}
