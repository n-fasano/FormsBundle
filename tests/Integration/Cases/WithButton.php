<?php

namespace Fasano\FormsBundle\Tests\Integration\Cases;

use Fasano\FormsBundle\Attribute\Form;

#[Form\Button(label: 'Submit')]
final readonly class WithButton
{
}