<?php

declare(strict_types=1);

namespace Fasano\FormsBundle\Tests\Unit\Configurator\Typedoc;

use Fasano\FormsBundle\Configurator\Typedoc\DescriptionConfigurator;
use Fasano\FormsBundle\Field\FieldConfig;
use Fasano\Typedocs;
use PHPUnit\Framework\TestCase;

class DescriptionConfiguratorTest extends TestCase
{
    private DescriptionConfigurator $configurator;

    protected function setUp(): void
    {
        $this->configurator = new DescriptionConfigurator();
    }

    public function testTypedoc(): void
    {
        self::assertSame(Typedocs\Description::class, $this->configurator->typedoc());
    }

    public function testConfigure(): void
    {
        $config = new FieldConfig();
        $this->configurator->configure($config, new Typedocs\Description('An email address'));

        self::assertSame('An email address', $config->options['help']);
    }
}
