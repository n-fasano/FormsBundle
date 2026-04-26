<?php

namespace Fasano\FormsBundle\Tests\Unit\Configurator\Constraint;

use Fasano\FormsBundle\Configurator\Constraint\FileConfigurator;
use Fasano\FormsBundle\Field\FieldConfig;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;

class FileConfiguratorTest extends TestCase
{
    private FileConfigurator $configurator;

    protected function setUp(): void
    {
        $this->configurator = new FileConfigurator();
    }

    public function testConstraint(): void
    {
        self::assertSame(File::class, $this->configurator->constraint());
    }

    public function testConfigure(): void
    {
        $config = new FieldConfig();

        $this->configurator->configure($config, new File());

        self::assertSame(FileType::class, $config->type);
        self::assertArrayNotHasKey('accept', $config->options['attr']);
    }

    public function testConfigureWithMimeTypes(): void
    {
        $config = new FieldConfig();

        $this->configurator->configure($config, new File(mimeTypes: ['application/pdf', 'image/png']));

        self::assertSame('application/pdf,image/png', $config->options['attr']['accept']);
    }

    public function testConfigureWithExtensions(): void
    {
        $config = new FieldConfig();

        $this->configurator->configure($config, new File(extensions: ['pdf', 'doc']));

        self::assertSame('.pdf,.doc', $config->options['attr']['accept']);
    }

    public function testConfigureWithMimeTypesAndExtensions(): void
    {
        $config = new FieldConfig();

        $this->configurator->configure($config, new File(mimeTypes: ['application/pdf'], extensions: ['pdf']));

        self::assertSame('application/pdf,.pdf', $config->options['attr']['accept']);
    }
}
