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

use Fasano\FormsBundle\Field\FieldAnalyzer;
use Fasano\FormsBundle\Template\EmptyDataTemplate;
use Fasano\FormsBundle\Template\FormTypeClassTemplate;
use Fasano\FormsBundle\Attribute\Form;
use Fasano\FormsBundle\Toolbox\CodeWriter;
use ReflectionClass;
use ReflectionProperty;
use Fasano\FormsBundle\Toolbox\ReflectionUtils;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\Constraints;

class FormTypeGenerator
{
    public function __construct(
        protected UrlGeneratorInterface $urlGenerator,
        protected FieldAnalyzer $fieldAnalyzer,
        protected FormTypeNamingStrategy $namingStrategy,
    ) {}

    public function generate(string $fqcn): string
    {
        $reflection = new ReflectionClass($fqcn);
        $namespace = $this->namingStrategy->getNamespace($fqcn);
        $className = $this->namingStrategy->getClassName($fqcn);

        $buildFormBody = $this->buildForm($reflection);
        $configureOptionsBody = $this->configureOptions($reflection, $fqcn);

        return new FormTypeClassTemplate(
            $namespace,
            $className,
            (string) $buildFormBody,
            (string) $configureOptionsBody,
        )->render();
    }

    protected function configureOptions(ReflectionClass $reflection, string $fqcn): CodeWriter
    {
        $formOptions = ReflectionUtils::findAttribute($reflection, Form\Options::class) ?? new Form\Options();

        if (isset($formOptions['action'])) {
            $formOptions['action'] = $this->urlGenerator->generate($formOptions['action']);
        }

        $formOptions['data_class'] = $fqcn;
        $formOptions['constraints'] = array_merge($formOptions['constraints'] ?? [], ['<valid>']);
        $formOptions['empty_data'] ??= '<empty_data>';

        $formOptions = var_export($formOptions->value, true);
        $formOptions = strtr($formOptions, ['\'<valid>\'' => 'new \\'. Constraints\Valid::class . '()']);
        $formOptions = strtr($formOptions, ['\'<empty_data>\'' => new EmptyDataTemplate($fqcn)->render()]);

        return new CodeWriter()
            ->line('$resolver->setDefaults(')
            ->append($formOptions, indent: 3)
            ->append(');', indent: 2);
    }

    protected function buildForm(ReflectionClass $reflection): CodeWriter
    {
        /** @var ReflectionProperty[] $properties */
        $properties = $reflection->getProperties(ReflectionProperty::IS_PUBLIC);

        $adds = new CodeWriter()
            ->line('$builder');

        foreach ($properties as $property) {
            $field = $this->fieldAnalyzer->analyze($property);
            $fieldOptions = var_export($field->options, true);

            $builderCall = new CodeWriter()
                ->line('$builder')
                ->indent(fn (CodeWriter $w): CodeWriter => $w
                    ->line("->create(")
                    ->indent(fn (CodeWriter $w): CodeWriter => $w
                        ->line("'{$field->name}',")
                        ->line("\\{$field->type}::class,")
                        ->append("{$fieldOptions},")
                    )
                    ->line(')')
                );

            if (null !== $field->transformer) {
                $builderCall
                    ->indent(fn (CodeWriter $w): CodeWriter => $w
                        ->line('->addModelTransformer(')
                        ->append($field->transformer, indent: 1)
                        ->line(')')
                    );
            }

            $adds
                ->append('->add(', indent: 3)
                ->append($builderCall, indent: 4)
                ->append(')', indent: 3);
        }

        if ($button = ReflectionUtils::findAttribute($reflection, Form\Button::class)) {
            $submitType = SubmitType::class;
            $buttonOptions = var_export($button->options, true);

            $adds
                ->append('->add(', indent: 3)
                ->append('\'submit\',', indent: 4)
                ->append("\\{$submitType}::class,", indent: 4)
                ->append("{$buttonOptions},", indent: 4)
                ->append(')', indent: 3);
        }

        return $adds;
    }
}