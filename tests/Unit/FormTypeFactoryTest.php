<?php

declare(strict_types=1);

namespace Fasano\FormsBundle\Tests\Unit;

use Fasano\FormsBundle\Form\FormTypeGenerator;
use Fasano\FormsBundle\Form\FormTypeNamingStrategy;
use Fasano\FormsBundle\Form\TypedForm;
use Fasano\FormsBundle\FormTypeFactory;
use Fasano\FormsBundle\Form\FormTypeGeneratorFactory;
use Fasano\FormsBundle\Tests\Integration\TestFormTypeFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

class SomeDto {}

class FormTypeFactoryTest extends TestCase
{
    private FormTypeNamingStrategy $namingStrategy;

    protected function setUp(): void
    {
        $this->namingStrategy = new FormTypeNamingStrategy(
            'Cache\\Forms',
            sys_get_temp_dir() . '/forms_bundle_tests' . uniqid() . '/',
        );
    }

    private function makeFactory(): array
    {
        $formFactory = $this->createMock(FormFactoryInterface::class);
        $factory = new TestFormTypeFactory(
            $this->namingStrategy,
            formFactory: $formFactory,
            generatorFactory: $this->createStub(FormTypeGeneratorFactory::class),
            filesystem: new Filesystem(),
        );

        return [$formFactory, $factory];
    }

    public function testCreateFormReturnsTypedForm(): void
    {
        [$formFactory, $factory] = $this->makeFactory();
        $expectedFqcn = 'Cache\\Forms\\' . __NAMESPACE__ . '\\SomeDtoType';
        $inner = $this->createStub(FormInterface::class);
        $formFactory->expects(self::once())->method('create')->with($expectedFqcn, null, [])->willReturn($inner);

        $result = $factory->createForm(SomeDto::class);

        self::assertInstanceOf(TypedForm::class, $result);
    }

    public function testCreateFormPassesDataAndOptions(): void
    {
        [$formFactory, $factory] = $this->makeFactory();
        $expectedFqcn = 'Cache\\Forms\\' . __NAMESPACE__ . '\\SomeDtoType';
        $data = new SomeDto();
        $inner = $this->createStub(FormInterface::class);
        $formFactory->expects(self::once())->method('create')->with($expectedFqcn, $data, ['attr' => []])->willReturn($inner);

        $result = $factory->createForm(SomeDto::class, $data, ['attr' => []]);

        self::assertInstanceOf(TypedForm::class, $result);
    }

    public function testCreateFormBuilderReturnsBuilder(): void
    {
        [$formFactory, $factory] = $this->makeFactory();
        $expectedFqcn = 'Cache\\Forms\\' . __NAMESPACE__ . '\\SomeDtoType';
        $builder = $this->createStub(FormBuilderInterface::class);
        $formFactory->expects(self::once())->method('createBuilder')->with($expectedFqcn, null, [])->willReturn($builder);

        $result = $factory->createFormBuilder(SomeDto::class);

        self::assertSame($builder, $result);
    }

    public function testCreateFormTypeGeneratesAndRequiresFile(): void
    {
        $generator = $this->createStub(FormTypeGenerator::class);
        $generator->method('generate')->willReturn('<?php // generated');

        $generatorFactory = $this->createStub(FormTypeGeneratorFactory::class);
        $generatorFactory->method('create')->willReturn($generator);

        $factory = new FormTypeFactory(
            namingStrategy: $this->namingStrategy,
            formFactory: $this->createStub(FormFactoryInterface::class),
            generatorFactory: $generatorFactory,
            filesystem: new Filesystem(),
        );

        $fqcn = $factory->createFormType(SomeDto::class);

        self::assertSame('Cache\\Forms\\' . __NAMESPACE__ . '\\SomeDtoType', $fqcn);
    }

    public function testCreateFormTypeSkipsGenerationWhenCacheHit(): void
    {
        $generator = $this->createMock(FormTypeGenerator::class);
        $generator->expects(self::never())->method('generate');

        $generatorFactory = $this->createStub(FormTypeGeneratorFactory::class);
        $generatorFactory->method('create')->willReturn($generator);

        $filesystem = new Filesystem();

        // Pre-populate the cache file so the cache is considered warm
        $path = $this->namingStrategy->getPath(SomeDto::class);
        $fqcn = $this->namingStrategy->getFqcn(SomeDto::class);

        $filesystem->dumpFile($path, '<?php // cached');

        $factory = new FormTypeFactory(
            namingStrategy: $this->namingStrategy,
            formFactory: $this->createStub(FormFactoryInterface::class),
            generatorFactory: $generatorFactory,
            filesystem: $filesystem,
            enableCache: true,
        );

        $fqcn = $factory->createFormType(SomeDto::class);

        self::assertSame($fqcn, $fqcn);
    }
}
