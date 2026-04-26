<?php

namespace Fasano\FormsBundle\Tests\Integration\Cases;

use Fasano\FormsBundle\Attribute\Field;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

final readonly class WithType
{
    public function __construct(
        #[Field\Type(EmailType::class)]
        public string $email,
    ) {}
}
