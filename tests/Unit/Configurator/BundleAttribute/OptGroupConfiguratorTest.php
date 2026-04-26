<?php

declare(strict_types=1);

namespace Fasano\FormsBundle\Tests\Unit\Configurator\BundleAttribute;

use Fasano\FormsBundle\Attribute\Field\OptGroup;
use Fasano\FormsBundle\Configurator\BundleAttribute\OptGroupConfigurator;
use Fasano\FormsBundle\Field\FieldConfig;
use Fasano\FormsBundle\Field\FieldContext;
use Fasano\FormsBundle\FormTypeFactory;
use PHPUnit\Framework\TestCase;
use ReflectionProperty;
use Symfony\Component\DependencyInjection\ContainerInterface;

class OptGroupItem
{
    public function __construct(
        public string $name,
        public string $value,
    ) {}
}

class OptGroupItemProvider
{
    public static function all(): array
    {
        return [
            new OptGroupItem('Alpha', 'alpha'),
            new OptGroupItem('Beta', 'beta'),
        ];
    }

    public static function attrFor(OptGroupItem $item): array
    {
        return ['data-val' => $item->value];
    }
}

class OptGroupConfiguratorTest extends TestCase
{
    public string $stub;

    private OptGroupConfigurator $configurator;
    private FieldContext $context;

    protected function setUp(): void
    {
        $container = $this->createStub(ContainerInterface::class);
        $this->configurator = new OptGroupConfigurator($container);

        $property = new ReflectionProperty(self::class, 'stub');
        $this->context = new FieldContext($property->getType(), [], $this->createStub(FormTypeFactory::class), $property);
    }

    public function testAttribute(): void
    {
        self::assertSame(OptGroup::class, $this->configurator->attribute());
    }

    public function testStaticProviderPopulatesChoices(): void
    {
        $config = new FieldConfig();
        $attribute = new OptGroup(
            provider: [OptGroupItemProvider::class, 'all'],
            isStatic: true,
        );

        $this->configurator->configure($config, $attribute, $this->context);

        self::assertSame(['Alpha' => 'alpha', 'Beta' => 'beta'], $config->options['choices']);
    }

    public function testLabelCreatesChoiceGroup(): void
    {
        $config = new FieldConfig();
        $attribute = new OptGroup(
            provider: [OptGroupItemProvider::class, 'all'],
            label: 'Greek Letters',
            isStatic: true,
        );

        $this->configurator->configure($config, $attribute, $this->context);

        self::assertArrayHasKey('Greek Letters', $config->options['choices']);
        self::assertSame(['Alpha' => 'alpha', 'Beta' => 'beta'], $config->options['choices']['Greek Letters']);
    }

    public function testMergesWithExistingChoices(): void
    {
        $config = new FieldConfig(options: ['choices' => ['Existing' => 'existing'], 'attr' => []]);
        $attribute = new OptGroup(
            provider: [OptGroupItemProvider::class, 'all'],
            isStatic: true,
        );

        $this->configurator->configure($config, $attribute, $this->context);

        self::assertArrayHasKey('Existing', $config->options['choices']);
        self::assertArrayHasKey('Alpha', $config->options['choices']);
    }

    public function testCallableAttributesPopulatesChoiceAttr(): void
    {
        $config = new FieldConfig();
        $attribute = new OptGroup(
            provider: [OptGroupItemProvider::class, 'all'],
            attributes: [OptGroupItemProvider::class, 'attrFor'],
            isStatic: true,
        );

        $this->configurator->configure($config, $attribute, $this->context);

        self::assertSame(['data-val' => 'alpha'], $config->options['choice_attr']['Alpha']);
        self::assertSame(['data-val' => 'beta'], $config->options['choice_attr']['Beta']);
    }

    public function testServiceProviderIsCalledViaContainer(): void
    {
        $provider = new OptGroupItemProvider();

        $container = $this->createMock(ContainerInterface::class);
        $container->expects(self::once())
            ->method('get')
            ->with(OptGroupItemProvider::class)
            ->willReturn($provider);

        $configurator = new OptGroupConfigurator($container);
        $config = new FieldConfig();
        $attribute = new OptGroup(
            provider: [OptGroupItemProvider::class, 'all'],
            isStatic: false,
        );

        $configurator->configure($config, $attribute, $this->context);

        self::assertArrayHasKey('Alpha', $config->options['choices']);
    }
}
