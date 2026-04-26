<?php

declare(strict_types=1);

namespace Fasano\FormsBundle\Tests\Unit;

use Fasano\FormsBundle\FormsBundle;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class FormsBundleTest extends TestCase
{
    public function testBuildDoesNotThrow(): void
    {
        $bundle = new FormsBundle();

        $bundle->build(new ContainerBuilder());

        $this->addToAssertionCount(1);
    }
}
