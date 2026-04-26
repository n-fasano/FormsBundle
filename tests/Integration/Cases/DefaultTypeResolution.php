<?php

namespace Fasano\FormsBundle\Tests\Integration\Cases;

use DateTime;
use DateTimeImmutable;

final class DefaultTypeResolution
{
    public function __construct(
        public string $string,
        public int $integer,
        public float $float,
        public bool $boolean,
        public DateTime $dateTime,
        public DateTimeImmutable $dateTimeImmutable,
        public array $array,
        public $defaultsToText,
        public ?string $nullable,
    ) {}
}