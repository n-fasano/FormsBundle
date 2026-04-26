<?php

declare(strict_types=1);

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
