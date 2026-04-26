<?php

declare(strict_types=1);

namespace Fasano\FormsBundle\Tests\Unit\Form;

use Fasano\FormsBundle\Form\TypedForm;
use PHPUnit\Framework\Attributes\AllowMockObjectsWithoutExpectations;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;

class TypedFormTest extends TestCase
{
    private FormInterface&MockObject $inner;
    private TypedForm $form;

    protected function setUp(): void
    {
        $this->inner = $this->createMock(FormInterface::class);
        $this->form = new TypedForm($this->inner);
    }

    public function testGetDataDelegates(): void
    {
        $data = new \stdClass();
        $this->inner->expects(self::once())->method('getData')->willReturn($data);

        self::assertSame($data, $this->form->getData());
    }

    public function testIsValidDelegates(): void
    {
        $this->inner->expects(self::once())->method('isValid')->willReturn(true);

        self::assertTrue($this->form->isValid());
    }

    public function testIsSubmittedDelegates(): void
    {
        $this->inner->expects(self::once())->method('isSubmitted')->willReturn(false);

        self::assertFalse($this->form->isSubmitted());
    }

    public function testSubmitDelegatesAndReturnsSelf(): void
    {
        $this->inner->expects(self::once())->method('submit')->with(['field' => 'value'], true);

        $result = $this->form->submit(['field' => 'value']);

        self::assertSame($this->form, $result);
    }

    public function testAddErrorDelegatesAndReturnsSelf(): void
    {
        $error = new FormError('oops');
        $this->inner->expects(self::once())->method('addError')->with($error);

        $result = $this->form->addError($error);

        self::assertSame($this->form, $result);
    }

    public function testGetNameDelegates(): void
    {
        $this->inner->expects(self::once())->method('getName')->willReturn('my_form');

        self::assertSame('my_form', $this->form->getName());
    }

    public function testHasDelegates(): void
    {
        $this->inner->expects(self::once())->method('has')->with('email')->willReturn(true);

        self::assertTrue($this->form->has('email'));
    }

    public function testCountDelegates(): void
    {
        $this->inner->expects(self::once())->method('count')->willReturn(3);

        self::assertSame(3, $this->form->count());
    }

    #[AllowMockObjectsWithoutExpectations]
    public function testGetIteratorReturnsInnerForm(): void
    {
        self::assertSame($this->inner, $this->form->getIterator());
    }

    public function testSetParentDelegatesAndReturnsSelf(): void
    {
        $parent = $this->createStub(FormInterface::class);
        $this->inner->expects(self::once())->method('setParent')->with($parent);

        self::assertSame($this->form, $this->form->setParent($parent));
    }

    public function testGetParentDelegates(): void
    {
        $parent = $this->createStub(FormInterface::class);
        $this->inner->expects(self::once())->method('getParent')->willReturn($parent);

        self::assertSame($parent, $this->form->getParent());
    }

    public function testAddDelegatesAndReturnsSelf(): void
    {
        $child = $this->createStub(FormInterface::class);
        $this->inner->expects(self::once())->method('add')->with($child, null, []);

        self::assertSame($this->form, $this->form->add($child));
    }

    public function testGetDelegates(): void
    {
        $child = $this->createStub(FormInterface::class);
        $this->inner->expects(self::once())->method('get')->with('email')->willReturn($child);

        self::assertSame($child, $this->form->get('email'));
    }

    public function testRemoveDelegatesAndReturnsSelf(): void
    {
        $this->inner->expects(self::once())->method('remove')->with('email');

        self::assertSame($this->form, $this->form->remove('email'));
    }

    public function testAllDelegates(): void
    {
        $child = $this->createStub(FormInterface::class);
        $this->inner->expects(self::once())->method('all')->willReturn(['email' => $child]);

        self::assertSame(['email' => $child], $this->form->all());
    }

    public function testGetErrorsDelegates(): void
    {
        $errors = $this->createStub(\Symfony\Component\Form\FormErrorIterator::class);
        $this->inner->expects(self::once())->method('getErrors')->with(false, true)->willReturn($errors);

        self::assertSame($errors, $this->form->getErrors());
    }

    public function testSetDataDelegatesAndReturnsSelf(): void
    {
        $this->inner->expects(self::once())->method('setData')->with('value');

        self::assertSame($this->form, $this->form->setData('value'));
    }

    public function testGetNormDataDelegates(): void
    {
        $this->inner->expects(self::once())->method('getNormData')->willReturn('norm');

        self::assertSame('norm', $this->form->getNormData());
    }

    public function testGetViewDataDelegates(): void
    {
        $this->inner->expects(self::once())->method('getViewData')->willReturn('view');

        self::assertSame('view', $this->form->getViewData());
    }

    public function testGetExtraDataDelegates(): void
    {
        $this->inner->expects(self::once())->method('getExtraData')->willReturn(['extra' => 1]);

        self::assertSame(['extra' => 1], $this->form->getExtraData());
    }

    public function testGetConfigDelegates(): void
    {
        $config = $this->createStub(\Symfony\Component\Form\FormConfigInterface::class);
        $this->inner->expects(self::once())->method('getConfig')->willReturn($config);

        self::assertSame($config, $this->form->getConfig());
    }

    public function testGetPropertyPathDelegates(): void
    {
        $path = $this->createStub(\Symfony\Component\PropertyAccess\PropertyPathInterface::class);
        $this->inner->expects(self::once())->method('getPropertyPath')->willReturn($path);

        self::assertSame($path, $this->form->getPropertyPath());
    }

    public function testIsRequiredDelegates(): void
    {
        $this->inner->expects(self::once())->method('isRequired')->willReturn(true);

        self::assertTrue($this->form->isRequired());
    }

    public function testIsDisabledDelegates(): void
    {
        $this->inner->expects(self::once())->method('isDisabled')->willReturn(false);

        self::assertFalse($this->form->isDisabled());
    }

    public function testIsEmptyDelegates(): void
    {
        $this->inner->expects(self::once())->method('isEmpty')->willReturn(true);

        self::assertTrue($this->form->isEmpty());
    }

    public function testIsSynchronizedDelegates(): void
    {
        $this->inner->expects(self::once())->method('isSynchronized')->willReturn(true);

        self::assertTrue($this->form->isSynchronized());
    }

    public function testGetTransformationFailureDelegates(): void
    {
        $this->inner->expects(self::once())->method('getTransformationFailure')->willReturn(null);

        self::assertNull($this->form->getTransformationFailure());
    }

    public function testInitializeDelegatesAndReturnsSelf(): void
    {
        $this->inner->expects(self::once())->method('initialize');

        self::assertSame($this->form, $this->form->initialize());
    }

    public function testHandleRequestDelegatesAndReturnsSelf(): void
    {
        $request = new \stdClass();
        $this->inner->expects(self::once())->method('handleRequest')->with($request);

        self::assertSame($this->form, $this->form->handleRequest($request));
    }

    public function testGetRootDelegates(): void
    {
        $root = $this->createStub(FormInterface::class);
        $this->inner->expects(self::once())->method('getRoot')->willReturn($root);

        self::assertSame($root, $this->form->getRoot());
    }

    public function testIsRootDelegates(): void
    {
        $this->inner->expects(self::once())->method('isRoot')->willReturn(true);

        self::assertTrue($this->form->isRoot());
    }

    public function testCreateViewDelegates(): void
    {
        $view = new \Symfony\Component\Form\FormView();
        $this->inner->expects(self::once())->method('createView')->with(null)->willReturn($view);

        self::assertSame($view, $this->form->createView());
    }

    public function testOffsetExistsDelegates(): void
    {
        $this->inner->expects(self::once())->method('offsetExists')->with('email')->willReturn(true);

        self::assertTrue(isset($this->form['email']));
    }

    public function testOffsetGetDelegates(): void
    {
        $child = $this->createStub(FormInterface::class);
        $this->inner->expects(self::once())->method('offsetGet')->with('email')->willReturn($child);

        self::assertSame($child, $this->form['email']);
    }

    public function testOffsetSetDelegates(): void
    {
        $this->inner->expects(self::once())->method('offsetSet')->with('email', 'value');

        $this->form['email'] = 'value';
    }

    public function testOffsetUnsetDelegates(): void
    {
        $this->inner->expects(self::once())->method('offsetUnset')->with('email');

        unset($this->form['email']);
    }
}
