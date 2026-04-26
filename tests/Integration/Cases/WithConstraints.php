<?php

namespace Fasano\FormsBundle\Tests\Integration\Cases;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class WithConstraints
{
    public function __construct(
        #[Assert\Url]
        public string $url,

        #[Assert\Email]
        public string $email,

        #[Assert\Choice(choices: ['foo', 'bar'])]
        public string $choice,

        #[Assert\DateTime]
        public string $dateTime,

        #[Assert\Date]
        public string $date,

        #[Assert\Time]
        public string $time,

        #[Assert\NotBlank]
        public string $notBlank,

        #[Assert\Length(min: 5, max: 10)]
        public string $length,

        #[Assert\Regex('/\\d{6}/')]
        public string $regex,

        #[Assert\Range(min: 3, max: 12)]
        public int $range,

        #[Assert\Positive]
        public int $positive,

        #[Assert\PositiveOrZero]
        public int $positiveOrZero,

        #[Assert\Negative]
        public int $negative,

        #[Assert\NegativeOrZero]
        public int $negativeOrZero,

        #[Assert\LessThan(3)]
        public int $lessThan,

        #[Assert\LessThanOrEqual(3)]
        public int $lessThanOrEqual,

        #[Assert\GreaterThan(3)]
        public int $greaterThan,

        #[Assert\GreaterThanOrEqual(3)]
        public int $greaterThanOrEqual,

        #[Assert\DivisibleBy(5)]
        public int $divisibleBy,

        #[Assert\NotNull]
        public ?string $notNull,

        #[Assert\IsTrue]
        public bool $isTrue,

        #[Assert\Uuid]
        public string $uuid,

        #[Assert\Bic]
        public string $bic,

        #[Assert\Iban]
        public string $iban,

        #[Assert\Isbn]
        public string $isbn,

        #[Assert\Isbn(type: 'isbn10')]
        public string $isbn10,

        #[Assert\Isbn(type: 'isbn13')]
        public string $isbn13,

        #[Assert\CardScheme(schemes: ['VISA', 'MASTERCARD'])]
        public string $card,

        #[Assert\PasswordStrength]
        public string $password,

        #[Assert\Ip]
        public string $ipv4,

        #[Assert\Ip(version: 'all')]
        public string $ip,

        #[Assert\Cidr]
        public string $cidr,

        #[Assert\Hostname]
        public string $hostname,

        #[Assert\MacAddress]
        public string $macAddress,

        #[Assert\Ulid]
        public string $ulid,

        #[Assert\Json]
        public string $json,
    ) {}
}
