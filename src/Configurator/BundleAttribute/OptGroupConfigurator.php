<?php

declare(strict_types=1);

namespace Fasano\FormsBundle\Configurator\BundleAttribute;

use Fasano\FormsBundle\Attribute\Field\OptGroup;
use Fasano\FormsBundle\Attribute\FieldAttribute;
use Fasano\FormsBundle\Configurator\BundleAttribute\BundleAttributeConfigurator;
use Fasano\FormsBundle\Field\FieldConfig;
use Fasano\FormsBundle\Field\FieldContext;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\DependencyInjection\ContainerInterface;

class OptGroupConfigurator implements BundleAttributeConfigurator
{
    public function __construct(
        #[Autowire(service: 'service_container')]
        protected ContainerInterface $container,
    ) {}

    public function attribute(): string
    {
        return OptGroup::class;
    }

    public function configure(FieldConfig $config, FieldAttribute $attribute, FieldContext $context): void
    {
        /** @var OptGroup $attribute */
        $choices = [];
        $toFill = &$choices;

        if (null !== $attribute->label) {
            $choices[$attribute->label] = [];
            $toFill = &$choices[$attribute->label];
        }

        $choicesAttributes = [];

        [$class, $method] = $attribute->provider;
        $items = $attribute->isStatic
            ? $class::{$method}()
            : $this->container->get($class)->{$method}();

        foreach ($items as $item) {
            $toFill[$item->{$attribute->optLabel}] = $item->{$attribute->optValue};
            $choicesAttributes[$item->{$attribute->optLabel}] = is_callable($attribute->attributes)
                ? ($attribute->attributes)($item)
                : $attribute->attributes;
        }

        $config->options['choices'] ??= [];
        $config->options['choices'] = array_merge($config->options['choices'], $choices);

        if (!empty($attribute->attributes)) {
            $config->options['choice_attr'] ??= [];
            $config->options['choice_attr'] = array_merge($config->options['choice_attr'], $choicesAttributes);
        }
    }
}