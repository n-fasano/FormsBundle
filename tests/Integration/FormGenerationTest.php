<?php

namespace Fasano\FormsBundle\Tests\Integration;

use Fasano\FormsBundle\Form\FormTypeGenerator;
use Fasano\FormsBundle\Form\FormTypeGeneratorFactory;
use Fasano\FormsBundle\Form\FormTypeNamingStrategy;
use Fasano\FormsBundle\Tests\Integration\Cases\DefaultTypeResolution;
use Fasano\FormsBundle\Tests\Integration\Cases\WithAction;
use Fasano\FormsBundle\Tests\Integration\Cases\WithAttributes;
use Fasano\FormsBundle\Tests\Integration\Cases\WithButton;
use Fasano\FormsBundle\Tests\Integration\Cases\WithCollection;
use Fasano\FormsBundle\Tests\Integration\Cases\WithConstraints;
use Fasano\FormsBundle\Tests\Integration\Cases\WithTypeConstraints;
use Fasano\FormsBundle\Tests\Integration\Cases\WithEnum;
use Fasano\FormsBundle\Tests\Integration\Cases\WithName;
use Fasano\FormsBundle\Tests\Integration\Cases\WithOptGroups;
use Fasano\FormsBundle\Tests\Integration\Cases\WithOptions;
use Fasano\FormsBundle\Tests\Integration\Cases\WithPrimitive;
use Fasano\FormsBundle\Tests\Integration\Cases\WithSubForm;
use Fasano\FormsBundle\Tests\Integration\Cases\WithTypedocs;
use Fasano\FormsBundle\Tests\Integration\Cases\WithType;
use Fasano\FormsBundle\Tests\Integration\Cases\WithUnion;
use Fasano\FormsBundle\Tests\Integration\Dto\Register;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\Forms;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RequestContext;

class FormGenerationTest extends TestCase
{
    protected FormTypeGenerator $generator;

    private static FormTypeGenerator $sharedGenerator;

    public static function setUpBeforeClass(): void
    {
        $urlGenerator = new class implements UrlGeneratorInterface {
            public function generate(string $name, array $parameters = [], int $referenceType = self::ABSOLUTE_PATH): string
            {
                return '/example';
            }

            public function setContext(RequestContext $context): void {}

            public function getContext(): RequestContext
            {
                return new RequestContext();
            }
        };

        $container = new ContainerBuilder();
        $container->setParameter('kernel.cache_dir', sys_get_temp_dir() . '/forms_bundle_tests');
        $container->register(UrlGeneratorInterface::class)->setSynthetic(true);
        $container->register(FormFactoryInterface::class)->setSynthetic(true);
        $container->register(Filesystem::class, Filesystem::class);
        (new YamlFileLoader($container, new FileLocator(__DIR__ . '/../../config')))->load('services.yaml');
        $container->findDefinition(FormTypeGeneratorFactory::class)->setPublic(true);
        $container->getAlias(FormTypeGeneratorFactory::class)->setPublic(true);
        $container->findDefinition(FormTypeNamingStrategy::class)
            ->setArgument('$namespace', '')
            ->setArgument('$directory', '');
        $container->compile();

        $container->set(UrlGeneratorInterface::class, $urlGenerator);
        $container->set(FormFactoryInterface::class, Forms::createFormFactory());

        /** @var FormTypeGeneratorFactory $generatorFactory */
        $generatorFactory = $container->get(FormTypeGeneratorFactory::class);

        $testFactory = new TestFormTypeFactory(
            new FormTypeNamingStrategy('', ''),
            Forms::createFormFactory(),
            $generatorFactory,
            new Filesystem(),
        );

        self::$sharedGenerator = $generatorFactory->create($testFactory);
    }

    public function setUp(): void
    {
        $this->generator = self::$sharedGenerator;
    }

    #[DataProvider('cases')]
    public function test(string $dtoFqcn, string $formPath): void
    {
        $content = $this->generator->generate($dtoFqcn);
        $expected = file_get_contents($formPath);

        self::assertSame($expected, $content);
    }

    public static function cases(): iterable
    {
        yield 'With button' => [WithButton::class, __DIR__ . '/Form/WithButtonType.php'];
        yield 'With action' => [WithAction::class, __DIR__ . '/Form/WithActionType.php'];
        yield 'With primitive' => [WithPrimitive::class, __DIR__ . '/Form/WithPrimitiveType.php'];
        yield 'With type docs' => [WithTypedocs::class, __DIR__ . '/Form/WithTypedocsType.php'];
        yield 'With attributes' => [WithAttributes::class, __DIR__ . '/Form/WithAttributesType.php'];
        yield 'With options' => [WithOptions::class, __DIR__ . '/Form/WithOptionsType.php'];
        yield 'With type' => [WithType::class, __DIR__ . '/Form/WithTypeType.php'];
        yield 'With name' => [WithName::class, __DIR__ . '/Form/WithNameType.php'];
        yield 'With opt groups' => [WithOptGroups::class, __DIR__ . '/Form/WithOptGroupsType.php'];
        yield 'With constraints' => [WithConstraints::class, __DIR__ . '/Form/WithConstraintsType.php'];
        yield 'With type constraints' => [WithTypeConstraints::class, __DIR__ . '/Form/WithTypeConstraintsType.php'];
        yield 'With enum' => [WithEnum::class, __DIR__ . '/Form/WithEnumType.php'];
        yield 'With subform' => [WithSubForm::class, __DIR__ . '/Form/WithSubFormType.php'];
        yield 'With collection' => [WithCollection::class, __DIR__ . '/Form/WithCollectionType.php'];

        yield 'Default type resolution' => [DefaultTypeResolution::class, __DIR__ . '/Form/DefaultTypeResolutionType.php'];

        yield 'Use case: registration' => [Register::class, __DIR__ . '/Form/RegisterType.php'];
    }

    #[DataProvider('exceptionCases')]
    public function testExceptions(string $dtoFqcn, string $exceptionMessage): void
    {
        $this->expectExceptionMessage($exceptionMessage);

        $this->generator->generate($dtoFqcn);
    }

    public static function exceptionCases(): iterable
    {
        yield 'With union' => [WithUnion::class, 'Union and intersection types are not supported in dynamic forms. Property: WithUnion::something'];
    }
}