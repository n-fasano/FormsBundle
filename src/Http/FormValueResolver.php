<?php

declare(strict_types=1);

namespace Fasano\FormsBundle\Http;

use Fasano\FormsBundle\Form\ErroredForm;
use Fasano\FormsBundle\FormTypeFactory;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\HttpKernel\Event\ControllerArgumentsEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class FormValueResolver implements ValueResolverInterface, EventSubscriberInterface
{
    public function __construct(
        protected readonly FormTypeFactory $formFactory,
    ) {
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $attribute = $argument->getAttributesOfType(MapFormPayload::class, ArgumentMetadata::IS_INSTANCEOF)[0]
            ?? null;

        if (!$attribute) {
            return [];
        }

        return [$attribute];
    }

    public function onKernelControllerArguments(ControllerArgumentsEvent $event): void
    {
        $arguments = $event->getArguments();

        foreach ($arguments as $i => $argument) {
            if (!$argument instanceof MapFormPayload) {
                continue;
            }

            $request = $event->getRequest();

            $form = $this->formFactory->createForm($argument->formDtoFqcn);
            $form->submit($request->getPayload()->all($form->getName()));

            $arguments[$i] = $form->isSubmitted() && $form->isValid()
                ? $form->getData()
                : new ErroredForm($form);
        }

        $event->setArguments($arguments);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER_ARGUMENTS => 'onKernelControllerArguments',
        ];
    }
}
