<?php

namespace Fasano\FormsBundle\Tests\Integration;

use Fasano\FormsBundle\Form\FormTypeGeneratorFactory;
use Fasano\FormsBundle\Form\FormTypeNamingStrategy;
use Fasano\FormsBundle\FormTypeFactory;
use Fasano\FormsBundle\Tests\Integration\Cases\DefaultTypeResolution;
use Fasano\FormsBundle\Tests\Integration\Cases\WithAction;
use Fasano\FormsBundle\Tests\Integration\Cases\WithAttributes;
use Fasano\FormsBundle\Tests\Integration\Cases\WithButton;
use Fasano\FormsBundle\Tests\Integration\Cases\WithConstraints;
use Fasano\FormsBundle\Tests\Integration\Cases\WithEnum;
use Fasano\FormsBundle\Tests\Integration\Cases\WithCollection;
use Fasano\FormsBundle\Tests\Integration\Cases\WithFile;
use Fasano\FormsBundle\Tests\Integration\Cases\WithName;
use Fasano\FormsBundle\Tests\Integration\Cases\WithTypeConstraints;
use Fasano\FormsBundle\Tests\Integration\Cases\WithOptGroups;
use Fasano\FormsBundle\Tests\Integration\Cases\WithOptions;
use Fasano\FormsBundle\Tests\Integration\Cases\WithPrimitive;
use Fasano\FormsBundle\Tests\Integration\Cases\WithSubForm;
use Fasano\FormsBundle\Tests\Integration\Cases\WithTypedocs;
use Fasano\FormsBundle\Tests\Integration\Cases\WithType;
use Fasano\FormsBundle\Tests\Integration\Dto\Register;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Symfony\Bridge\Twig\Extension\FormExtension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Bridge\Twig\Extension\TranslationExtension;
use Symfony\Bridge\Twig\Form\TwigRendererEngine;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Form\FormRenderer;
use Symfony\Component\Form\Forms;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RequestContext;
use Twig\Environment as TwigEnvironment;
use Twig\Loader\FilesystemLoader;
use Twig\RuntimeLoader\FactoryRuntimeLoader;

class FormTypeFactoryTest extends TestCase
{
    private static FormTypeFactory $factory;
    private static TwigEnvironment $twig;

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

        $tempDir = sys_get_temp_dir() . '/forms_bundle_tests/';

        self::$factory = new FormTypeFactory(
            new FormTypeNamingStrategy('', $tempDir),
            Forms::createFormFactory(),
            $generatorFactory,
            new Filesystem(),
        );

        $twigBridgeSrc = dirname((new ReflectionClass(FormExtension::class))->getFileName(), 2);
        $loader = new FilesystemLoader([$twigBridgeSrc . '/Resources/views/Form']);
        $twig = new TwigEnvironment($loader);
        $formEngine = new TwigRendererEngine(['form_div_layout.html.twig'], $twig);
        $renderer = new FormRenderer($formEngine);
        $twig->addRuntimeLoader(new FactoryRuntimeLoader([
            FormRenderer::class => fn() => $renderer,
        ]));
        $twig->addExtension(new FormExtension());
        $twig->addExtension(new TranslationExtension());
        self::$twig = $twig;
    }

    private static function prettyPrint(string $html): string
    {
        $dom = new \DOMDocument();
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        @$dom->loadHTML($html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

        return trim($dom->saveXML($dom->documentElement)) . "\n";
    }

    #[DataProvider('cases')]
    public function test(string $dtoFqcn, string $formPath): void
    {
        $form = self::$factory->createForm($dtoFqcn);
        $rendered = self::prettyPrint(
            self::$twig->createTemplate('{{ form(form) }}')->render(['form' => $form->createView()])
        );

        if (!file_exists($formPath)) {
            (new Filesystem())->dumpFile($formPath, $rendered);
            $this->markTestIncomplete("Fixture created: $formPath");
            return;
        }

        self::assertSame(file_get_contents($formPath), $rendered);
    }

    public function testWithTypeConstraintsRendersAllSelects(): void
    {
        $form = self::$factory->createForm(WithTypeConstraints::class);
        $rendered = self::$twig->createTemplate('{{ form(form) }}')->render(['form' => $form->createView()]);

        foreach (['country', 'currency', 'language', 'locale', 'timezone'] as $field) {
            self::assertStringContainsString("name=\"with_type_constraints[$field]\"", $rendered);
        }
    }

    public static function cases(): iterable
    {
        yield '["With button"]' => [WithButton::class, __DIR__ . '/Html/WithButtonType.html'];
        yield '["With action"]' => [WithAction::class, __DIR__ . '/Html/WithActionType.html'];
        yield '["With primitive"]' => [WithPrimitive::class, __DIR__ . '/Html/WithPrimitiveType.html'];
        yield '["With type docs"]' => [WithTypedocs::class, __DIR__ . '/Html/WithTypedocsType.html'];
        yield '["With attributes"]' => [WithAttributes::class, __DIR__ . '/Html/WithAttributesType.html'];
        yield '["With options"]' => [WithOptions::class, __DIR__ . '/Html/WithOptionsType.html'];
        yield '["With type"]' => [WithType::class, __DIR__ . '/Html/WithTypeType.html'];
        yield '["With name"]' => [WithName::class, __DIR__ . '/Html/WithNameType.html'];
        yield '["With opt groups"]' => [WithOptGroups::class, __DIR__ . '/Html/WithOptGroupsType.html'];
        yield '["With constraints"]' => [WithConstraints::class, __DIR__ . '/Html/WithConstraintsType.html'];
        yield '["With file"]' => [WithFile::class, __DIR__ . '/Html/WithFileType.html'];
        yield '["With enum"]' => [WithEnum::class, __DIR__ . '/Html/WithEnumType.html'];
        yield '["With subform"]' => [WithSubForm::class, __DIR__ . '/Html/WithSubFormType.html'];
        yield '["With collection"]' => [WithCollection::class, __DIR__ . '/Html/WithCollectionType.html'];

        yield '["Default type resolution"]' => [DefaultTypeResolution::class, __DIR__ . '/Html/DefaultTypeResolutionType.html'];

        yield '["Use case: registration"]' => [Register::class, __DIR__ . '/Html/RegisterType.html'];
    }
}
