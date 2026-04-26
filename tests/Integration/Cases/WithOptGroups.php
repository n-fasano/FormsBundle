<?php

namespace Fasano\FormsBundle\Tests\Integration\Cases;

use Fasano\FormsBundle\Attribute\Field;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

final readonly class WithOptGroups
{
    public function __construct(
        #[Field\Type(ChoiceType::class)]
        #[Field\OptGroup([SomeOptions::class, 'cases'], 'First Group')]
        #[Field\OptGroup([ChoiceProvider::class, 'choices'], 'Second Group')]
        public string $choice,
    ) {}
}

enum SomeOptions: string
{
    case A = 'a';
    case B = 'b';
}

class ChoiceProvider
{
    public static function choices(): array
    {
        return [
            new Choice('C', 'c'),
            new Choice('D', 'd'),
        ];
    }
}

readonly class Choice
{
    public function __construct(
        public string $name,
        public string $value,
    ) {}
}