<?php

declare(strict_types=1);

namespace Fasano\FormsBundle;

use Fasano\FormsBundle\Form\FormTypeGenerator;
use Fasano\FormsBundle\Form\FormTypeGeneratorFactory;
use Fasano\FormsBundle\Form\FormTypeNamingStrategy;
use Fasano\FormsBundle\Form\TypedForm;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormFactoryInterface;

readonly class FormTypeFactory
{
    protected FormTypeGenerator $generator;

    public function __construct(
        protected FormTypeNamingStrategy $namingStrategy,
        protected FormFactoryInterface $formFactory,
        FormTypeGeneratorFactory $generatorFactory,
        protected Filesystem $filesystem,
        protected bool $enableCache = false,
    ) {
        $this->generator = $generatorFactory->create($this);
    }

    /**
     * @template T
     * 
     * @param class-string<T> $fqcn
     * @param ?T $data
     * 
     * @return TypedForm<T>
     */
    public function createForm(string $fqcn, mixed $data = null, array $options = []): TypedForm
    {
        $formTypeFqcn = $this->createFormType($fqcn);

        return new TypedForm($this->formFactory->create($formTypeFqcn, $data, $options));
    }

    /**
     * @template T
     * 
     * @param class-string<T> $fqcn
     * @param ?T $data
     */
    public function createFormBuilder(string $fqcn, mixed $data = null, array $options = []): FormBuilderInterface
    {
        $formTypeFqcn = $this->createFormType($fqcn);

        return $this->formFactory->createBuilder($formTypeFqcn, $data, $options);
    }

    public function createFormType(string $fqcn): string
    {
        $path = $this->namingStrategy->getPath($fqcn);

        if (!$this->enableCache || !$this->filesystem->exists($path)) {
            $sourceCode = $this->generator->generate($fqcn);

            $this->filesystem->dumpFile($path, $sourceCode);
        }

        require_once $path;

        return $this->namingStrategy->getFqcn($fqcn);
    }
}