<?php

declare(strict_types=1);

namespace Fasano\FormsBundle\Configurator\Constraint;

use Fasano\FormsBundle\Configurator\Constraint\ConstraintConfigurator;
use Fasano\FormsBundle\Field\FieldConfig;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints;

class ImageConfigurator implements ConstraintConfigurator
{
    public function constraint(): string
    {
        return Constraints\Image::class;
    }

    /** @param Constraints\Image $constraint */
    public function configure(FieldConfig $config, Constraint $constraint): void
    {
        $config->type = FileType::class;

        $mimeTypes = (array) $constraint->mimeTypes;
        $extensions = (array) $constraint->extensions;

        if ($mimeTypes === [] && $extensions === []) {
            $config->options['attr']['accept'] = 'image/*';
            return;
        }

        $parts = array_filter([
            implode(',', $mimeTypes),
            implode(',', array_map(fn (string $ext) => '.' . $ext, $extensions)),
        ]);

        $config->options['attr']['accept'] = implode(',', $parts);
    }
}
