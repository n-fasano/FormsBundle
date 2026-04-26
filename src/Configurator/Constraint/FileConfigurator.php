<?php

declare(strict_types=1);

namespace Fasano\FormsBundle\Configurator\Constraint;

use Fasano\FormsBundle\Configurator\Constraint\ConstraintConfigurator;
use Fasano\FormsBundle\Field\FieldConfig;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints;

class FileConfigurator implements ConstraintConfigurator
{
    public function constraint(): string
    {
        return Constraints\File::class;
    }

    /** @param Constraints\File $constraint */
    public function configure(FieldConfig $config, Constraint $constraint): void
    {
        $config->type = FileType::class;

        $accept = $this->buildAccept((array) $constraint->mimeTypes, (array) $constraint->extensions);

        if ($accept !== '') {
            $config->options['attr']['accept'] = $accept;
        }
    }

    /**
     * @param string[] $mimeTypes
     * @param string[] $extensions
     */
    protected function buildAccept(array $mimeTypes, array $extensions): string
    {
        $parts = array_filter([
            implode(',', $mimeTypes),
            implode(',', array_map(fn (string $ext) => '.' . $ext, $extensions)),
        ]);

        return implode(',', $parts);
    }
}
