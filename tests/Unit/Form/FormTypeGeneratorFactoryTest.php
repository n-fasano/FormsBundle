<?php

declare(strict_types=1);

namespace Fasano\FormsBundle\Tests\Unit\Form;

use Fasano\FormsBundle\Form\FormTypeGenerator;
use Fasano\FormsBundle\Form\FormTypeGeneratorFactory;
use Fasano\FormsBundle\Form\FormTypeNamingStrategy;
use Fasano\FormsBundle\FormTypeFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class FormTypeGeneratorFactoryTest extends TestCase
{
    private function makeFactory(): FormTypeGeneratorFactory
    {
        return new FormTypeGeneratorFactory(
            urlGenerator: $this->createStub(UrlGeneratorInterface::class),
            fieldConfigurators: [],
            namingStrategy: new FormTypeNamingStrategy('', ''),
        );
    }

    public function testCreateReturnsFormTypeGenerator(): void
    {
        $generator = $this->makeFactory()->create(
            $this->createStub(FormTypeFactory::class),
        );

        self::assertInstanceOf(FormTypeGenerator::class, $generator);
    }

    public function testCreateWithEmptyPrefixReturnsGenerator(): void
    {
        $generator = $this->makeFactory()->create(
            $this->createStub(FormTypeFactory::class),
        );

        self::assertInstanceOf(FormTypeGenerator::class, $generator);
    }
}
