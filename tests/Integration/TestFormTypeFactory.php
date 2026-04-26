<?php

declare(strict_types=1);

namespace Fasano\FormsBundle\Tests\Integration;

use Fasano\FormsBundle\FormTypeFactory;

readonly class TestFormTypeFactory extends FormTypeFactory
{
    public function createFormType(string $fqcn): string
    {
        return $this->namingStrategy->getFqcn($fqcn);
    }
}
