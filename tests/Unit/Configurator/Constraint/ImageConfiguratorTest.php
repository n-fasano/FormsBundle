<?php

namespace Fasano\FormsBundle\Tests\Unit\Configurator\Constraint;

use Fasano\FormsBundle\Configurator\Constraint\ImageConfigurator;
use Fasano\FormsBundle\Field\FieldConfig;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\Image;

class ImageConfiguratorTest extends TestCase
{
    private ImageConfigurator $configurator;

    protected function setUp(): void
    {
        $this->configurator = new ImageConfigurator();
    }

    public function testConstraint(): void
    {
        self::assertSame(Image::class, $this->configurator->constraint());
    }

    public function testConfigure(): void
    {
        $config = new FieldConfig();

        $this->configurator->configure($config, new Image());

        self::assertSame(FileType::class, $config->type);
    }

    public function testConfigureDefaultsToImageWildcardAccept(): void
    {
        $config = new FieldConfig();

        $this->configurator->configure($config, new Image());

        self::assertSame('image/*', $config->options['attr']['accept']);
    }

    public function testConfigureWithMimeTypes(): void
    {
        $config = new FieldConfig();

        $this->configurator->configure($config, new Image(mimeTypes: ['image/png', 'image/webp']));

        self::assertSame('image/png,image/webp', $config->options['attr']['accept']);
    }

    public function testConfigureWithExtensions(): void
    {
        $config = new FieldConfig();

        $this->configurator->configure($config, new Image(extensions: ['png', 'webp']));

        self::assertSame('.png,.webp', $config->options['attr']['accept']);
    }

    public function testConfigureWithMimeTypesAndExtensions(): void
    {
        $config = new FieldConfig();

        $this->configurator->configure($config, new Image(mimeTypes: ['image/png'], extensions: ['png']));

        self::assertSame('image/png,.png', $config->options['attr']['accept']);
    }
}
