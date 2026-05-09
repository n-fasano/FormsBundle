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

namespace Fasano\FormsBundle\Configurator;

use DateTime;
use DateTimeImmutable;
use Fasano\FormsBundle\Field\FieldConfig;
use Fasano\FormsBundle\Field\FieldConfigurator;
use Fasano\FormsBundle\Field\FieldContext;
use ReflectionClass;
use Fasano\FormsBundle\Toolbox\StringUtils;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class TypesystemConfigurator implements FieldConfigurator
{
    public function configure(FieldContext $context): FieldConfig
    {
        $config = new FieldConfig();
        $type = $context->type;

        $config->name = $context->property->getName();
        $config->options['required'] = !$type->allowsNull();
        $config->options['label'] = StringUtils::toTitleCase($context->property->getName());

        if ($type->isBuiltin()) {
            $config->type = match ($type->getName()) {
                'string' => TextType::class,
                'int'    => IntegerType::class,
                'float'  => NumberType::class,
                'bool'   => CheckboxType::class,
                'array'  => CollectionType::class,
                default  => TextType::class,
            };

            return $config;
        }

        if ($type->getName() === DateTime::class) {
            $config->type = DateTimeType::class;
            $config->options['input'] = 'datetime';
            return $config;
        }

        if ($type->getName() === DateTimeImmutable::class) {
            $config->type = DateTimeType::class;
            $config->options['input'] = 'datetime_immutable';
            return $config;
        }

        $reflection = new ReflectionClass($type->getName());

        if ($reflection->isEnum()) {
            $config->type = EnumType::class;
            $config->options['class'] = $reflection->getName();
            return $config;
        }

        if ($type->getName() === UploadedFile::class) {
            $config->type = FileType::class;
            return $config;
        }

        $config->type = $context->factory->createFormType($type->getName());

        return $config;
    }
}