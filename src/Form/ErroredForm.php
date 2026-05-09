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

use Fasano\FormsBundle\Form\TypedForm;

/**
 * @template T of object
 * 
 * @extends TypedForm<T>
 */
final class ErroredForm extends TypedForm
{
}