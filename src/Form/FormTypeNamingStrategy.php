<?php

declare(strict_types=1);

namespace Fasano\FormsBundle\Form;

use ReflectionClass;

class FormTypeNamingStrategy
{
    public function __construct(
        protected string $namespace,
        protected string $directory,
    ) {}

    public function getFqcn(string $fqcn): string
    {
        $className = $this->getClassName($fqcn);

        return $this->getNamespace($fqcn) . '\\' . $className;
    }

    public function getPath(string $fqcn): string
    {
        $className = $this->getClassName($fqcn);

        return "{$this->directory}{$className}.php";
    }

    public function getNamespace(string $fqcn): string
    {
        $sourceNamespace = (new ReflectionClass($fqcn))->getNamespaceName();

        return $this->namespace
            ? $this->namespace . '\\' . $sourceNamespace
            : $sourceNamespace;
    }

    public function getClassName(string $fqcn): string
    {
        return (new ReflectionClass($fqcn))->getShortName() . 'Type';
    }
}
