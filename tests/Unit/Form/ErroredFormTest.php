<?php

declare(strict_types=1);

namespace Fasano\FormsBundle\Tests\Unit\Form;

use Fasano\FormsBundle\Form\ErroredForm;
use Fasano\FormsBundle\Form\TypedForm;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormInterface;

class ErroredFormTest extends TestCase
{
    public function testIsInstanceOfTypedForm(): void
    {
        $inner = $this->createStub(FormInterface::class);
        $form = new ErroredForm($inner);

        self::assertInstanceOf(TypedForm::class, $form);
    }

    public function testGetDataDelegates(): void
    {
        $data = new \stdClass();
        $inner = $this->createMock(FormInterface::class);
        $inner->expects(self::once())->method('getData')->willReturn($data);

        $form = new ErroredForm($inner);

        self::assertSame($data, $form->getData());
    }

    public function testIsValidDelegates(): void
    {
        $inner = $this->createMock(FormInterface::class);
        $inner->expects(self::once())->method('isValid')->willReturn(false);

        $form = new ErroredForm($inner);

        self::assertFalse($form->isValid());
    }
}
