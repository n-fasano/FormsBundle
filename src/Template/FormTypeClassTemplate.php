<?php

declare(strict_types=1);

namespace Fasano\FormsBundle\Template;

final class FormTypeClassTemplate
{
    public function __construct(
        private readonly string $namespace,
        private readonly string $className,
        private readonly string $adds,
        private readonly string $configureOptionsBody,
    ) {}

    public function render(): string
    {
        return <<<EOL
        <?php

        namespace {$this->namespace};

        use Symfony\Component\Form\AbstractType;
        use Symfony\Component\Form\FormInterface;
        use Symfony\Component\Form\FormBuilderInterface;
        use Symfony\Component\OptionsResolver\OptionsResolver;

        class {$this->className} extends AbstractType
        {
            public function buildForm(FormBuilderInterface \$builder, array \$options): void
            {
                {$this->adds};
            }

            public function configureOptions(OptionsResolver \$resolver): void
            {
                {$this->configureOptionsBody}
            }
        }
        EOL;
    }
}