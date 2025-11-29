<?php

namespace Fasano\FormsBundle\Form;

use Symfony\Component\Form\FormInterface as BaseFormInterface;
use Symfony\Component\Form\Exception;

/**
 * @template T
 */
interface FormInterface extends BaseFormInterface
{
    /**
     * @return T
     *
     * @throws Exception\RuntimeException If the form inherits data but has no parent
     */
    public function getData(): mixed;
}