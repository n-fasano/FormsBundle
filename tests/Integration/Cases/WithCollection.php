<?php

namespace Fasano\FormsBundle\Tests\Integration\Cases;

use Fasano\FormsBundle\Attribute\Field\EntryType;

final readonly class WithCollection
{
    public function __construct(
        #[EntryType(CollectionItem::class)]
        public array $items,
    ) {}
}

final readonly class CollectionItem
{
    public function __construct(
        public string $label,
    ) {}
}
