<?php

declare(strict_types=1);

namespace Fasano\FormsBundle\Template;

final class EmptyDataTemplate
{
    public function __construct(
        private readonly string $fqcn,
    ) {}

    public function render(): string
    {
        return <<<EOL
        function (FormInterface \$form) {
            try {
                return new \\{$this->fqcn}(...array_values(array_map(
                    fn (FormInterface \$child) => \$child->getData(),
                    \$form->all(),
                )));
            } catch (\\InvalidArgumentException \$e) {
                throw new \Symfony\Component\Form\Exception\TransformationFailedException(
                    message: \$e->getMessage(),
                    previous: \$e,
                );
            } catch (\\Throwable \$e) {
                throw new \Symfony\Component\Form\Exception\TransformationFailedException(
                    message: 'Invalid form',
                    previous: \$e,
                );
            }
        }
        EOL;
    }
}