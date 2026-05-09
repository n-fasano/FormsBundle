<?php

declare(strict_types=1);

/*
 * This file is part of the FormsBundle package.
 *
 * (c) Nicolas Fasano <fasano.nm@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
