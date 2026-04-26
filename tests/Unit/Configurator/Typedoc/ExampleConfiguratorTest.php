<?php

declare(strict_types=1);

namespace Fasano\FormsBundle\Tests\Unit\Configurator\Typedoc;

use Fasano\FormsBundle\Configurator\Typedoc\ExampleConfigurator;
use Fasano\FormsBundle\Field\FieldConfig;
use Fasano\Typedocs;
use PHPUnit\Framework\TestCase;

class ExampleConfiguratorTest extends TestCase
{
    private ExampleConfigurator $configurator;

    protected function setUp(): void
    {
        $this->configurator = new ExampleConfigurator();
    }

    public function testTypedoc(): void
    {
        self::assertSame(Typedocs\Example::class, $this->configurator->typedoc());
    }

    public function testConfigure(): void
    {
        $config = new FieldConfig();
        $this->configurator->configure($config, new Typedocs\Example('john@example.com'));

        self::assertSame('john@example.com', $config->options['attr']['placeholder']);
    }
}
