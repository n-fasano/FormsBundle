<?php

namespace Fasano\FormsBundle\Tests\Integration\Cases;

use Fasano\FormsBundle\Attribute\Form;

#[Form\Options(action: 'example')]
final readonly class WithAction
{
}