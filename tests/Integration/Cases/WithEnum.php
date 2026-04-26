<?php

namespace Fasano\FormsBundle\Tests\Integration\Cases;

final readonly class WithEnum
{
    public function __construct(
        public Color $color,
    ) {}
}

enum Color: string
{
    case RED = 'red';
    case GREEN = 'green';
    case BLUE = 'blue';
}