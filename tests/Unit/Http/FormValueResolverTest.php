<?php

declare(strict_types=1);

namespace Fasano\FormsBundle\Tests\Unit\Http;

use Fasano\FormsBundle\Form\ErroredForm;
use Fasano\FormsBundle\Form\TypedForm;
use Fasano\FormsBundle\FormTypeFactory;
use Fasano\FormsBundle\Http\FormValueResolver;
use Fasano\FormsBundle\Http\MapFormPayload;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\HttpKernel\Event\ControllerArgumentsEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;

class FormValueResolverTest extends TestCase
{
    private FormValueResolver $resolver;

    protected function setUp(): void
    {
        $this->resolver = new FormValueResolver($this->createStub(FormTypeFactory::class));
    }

    public function testImplementsInterfaces(): void
    {
        self::assertInstanceOf(ValueResolverInterface::class, $this->resolver);
        self::assertInstanceOf(EventSubscriberInterface::class, $this->resolver);
    }

    public function testGetSubscribedEvents(): void
    {
        $events = FormValueResolver::getSubscribedEvents();

        self::assertArrayHasKey(KernelEvents::CONTROLLER_ARGUMENTS, $events);
        self::assertSame('onKernelControllerArguments', $events[KernelEvents::CONTROLLER_ARGUMENTS]);
    }

    public function testResolveReturnsEmptyWhenNoAttribute(): void
    {
        $request = Request::create('/');
        $argument = new ArgumentMetadata('dto', null, false, false, null);

        $result = $this->resolver->resolve($request, $argument);

        self::assertSame([], $result);
    }

    public function testResolveReturnsAttributeWhenPresent(): void
    {
        $request = Request::create('/');
        $attribute = new MapFormPayload(formDtoFqcn: 'App\\Dto\\Register');
        $argument = new ArgumentMetadata('dto', null, false, false, null, false, [$attribute]);

        $result = $this->resolver->resolve($request, $argument);

        self::assertCount(1, $result);
        self::assertSame($attribute, $result[0]);
    }

    public function testOnKernelControllerArgumentsSkipsNonMapFormPayload(): void
    {
        $factory = $this->createMock(FormTypeFactory::class);
        $resolver = new FormValueResolver($factory);

        $kernel = $this->createStub(HttpKernelInterface::class);
        $request = Request::create('/');
        $event = new ControllerArgumentsEvent($kernel, fn() => null, ['not_a_payload'], $request, HttpKernelInterface::MAIN_REQUEST);

        $factory->expects(self::never())->method('createForm');

        $resolver->onKernelControllerArguments($event);

        self::assertSame(['not_a_payload'], $event->getArguments());
    }

    public function testOnKernelControllerArgumentsReplacesWithDataOnValidForm(): void
    {
        $factory = $this->createMock(FormTypeFactory::class);
        $resolver = new FormValueResolver($factory);

        $dto = new \stdClass();
        $inner = $this->createStub(FormInterface::class);
        $inner->method('getName')->willReturn('register');
        $inner->method('isSubmitted')->willReturn(true);
        $inner->method('isValid')->willReturn(true);
        $inner->method('getData')->willReturn($dto);
        $typedForm = new TypedForm($inner);

        $factory->expects(self::once())->method('createForm')->willReturn($typedForm);

        $kernel = $this->createStub(HttpKernelInterface::class);
        $request = Request::create('/submit', 'POST');
        $payload = new MapFormPayload(formDtoFqcn: 'App\\Dto\\Register');
        $event = new ControllerArgumentsEvent($kernel, fn() => null, [$payload], $request, HttpKernelInterface::MAIN_REQUEST);

        $resolver->onKernelControllerArguments($event);

        self::assertSame($dto, $event->getArguments()[0]);
    }

    public function testOnKernelControllerArgumentsReplacesWithErroredFormOnInvalid(): void
    {
        $factory = $this->createMock(FormTypeFactory::class);
        $resolver = new FormValueResolver($factory);

        $inner = $this->createStub(FormInterface::class);
        $inner->method('getName')->willReturn('register');
        $inner->method('isSubmitted')->willReturn(true);
        $inner->method('isValid')->willReturn(false);
        $typedForm = new TypedForm($inner);

        $factory->expects(self::once())->method('createForm')->willReturn($typedForm);

        $kernel = $this->createStub(HttpKernelInterface::class);
        $request = Request::create('/submit', 'POST');
        $payload = new MapFormPayload(formDtoFqcn: 'App\\Dto\\Register');
        $event = new ControllerArgumentsEvent($kernel, fn() => null, [$payload], $request, HttpKernelInterface::MAIN_REQUEST);

        $resolver->onKernelControllerArguments($event);

        self::assertInstanceOf(ErroredForm::class, $event->getArguments()[0]);
    }

    public function testOnKernelControllerArgumentsCallsCreateFormWithFqcn(): void
    {
        $factory = $this->createMock(FormTypeFactory::class);
        $resolver = new FormValueResolver($factory);

        $inner = $this->createStub(FormInterface::class);
        $inner->method('getName')->willReturn('register');
        $inner->method('isSubmitted')->willReturn(false);
        $inner->method('isValid')->willReturn(false);
        $typedForm = new TypedForm($inner);

        $factory->expects(self::once())
            ->method('createForm')
            ->with('App\\Dto\\Register')
            ->willReturn($typedForm);

        $kernel = $this->createStub(HttpKernelInterface::class);
        $request = Request::create('/submit', 'POST');
        $payload = new MapFormPayload(formDtoFqcn: 'App\\Dto\\Register');
        $event = new ControllerArgumentsEvent($kernel, fn() => null, [$payload], $request, HttpKernelInterface::MAIN_REQUEST);

        $resolver->onKernelControllerArguments($event);
    }
}
