<?php

declare(strict_types=1);

namespace Fasano\FormsBundle\Tests\Unit\Configurator\Typedoc;

use Fasano\FormsBundle\Configurator\Typedoc\NameConfigurator;
use Fasano\FormsBundle\Field\FieldConfig;
use Fasano\Typedocs;
use PHPUnit\Framework\TestCase;

class NameConfiguratorTest extends TestCase
{
    private NameConfigurator $configurator;

    protected function setUp(): void
    {
        $this->configurator = new NameConfigurator();
    }

    public function testTypedoc(): void
    {
        self::assertSame(Typedocs\Name::class, $this->configurator->typedoc());
    }

    public function testConfigure(): void
    {
        $config = new FieldConfig();
        $this->configurator->configure($config, new Typedocs\Name('Email Address'));

        self::assertSame('Email Address', $config->options['label']);
    }
}
