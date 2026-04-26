<?php

namespace Fasano\FormsBundle\Tests\Integration\Dto;

use Fasano\FormsBundle\Attribute\Field;
use Fasano\FormsBundle\Attribute\Form;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

#[Form\Options(action: 'app.register')]
#[Form\Button(label: 'Register')]
final readonly class Register
{
    public function __construct(
        #[Field\Type(EmailType::class)]
        public Email $email,

        #[Field\Type(PasswordType::class)]
        public Password $password,
    ) {}
}