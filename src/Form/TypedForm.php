<?php

declare(strict_types=1);

namespace Fasano\FormsBundle\Form;

use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormErrorIterator;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormConfigInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\PropertyAccess\PropertyPathInterface;

/**
 * @template T of object
 */
class TypedForm implements FormInterface, \IteratorAggregate
{
    public function __construct(
        private readonly FormInterface $inner,
    ) {}
    
    /**
     * @return T
     */
    public function getData(): mixed
    {
        return $this->inner->getData();
    }

    public function isValid(): bool { return $this->inner->isValid(); }
    public function submit(string|array|null $submittedData, bool $clearMissing = true): static { $this->inner->submit($submittedData, $clearMissing); return $this; }
    public function setParent(?FormInterface $parent): static { $this->inner->setParent($parent); return $this; }
    public function getParent(): ?FormInterface { return $this->inner->getParent(); }
    public function add(FormInterface|string $child, ?string $type = null, array $options = []): static { $this->inner->add($child, $type, $options); return $this; }
    public function get(string $name): FormInterface { return $this->inner->get($name); }
    public function has(string $name): bool { return $this->inner->has($name); }
    public function remove(string $name): static { $this->inner->remove($name); return $this; }
    public function all(): array { return $this->inner->all(); }
    public function getErrors(bool $deep = false, bool $flatten = true): FormErrorIterator { return $this->inner->getErrors($deep, $flatten); }
    public function setData(mixed $modelData): static { $this->inner->setData($modelData); return $this; }
    public function getNormData(): mixed { return $this->inner->getNormData(); }
    public function getViewData(): mixed { return $this->inner->getViewData(); }
    public function getExtraData(): array { return $this->inner->getExtraData(); }
    public function getConfig(): FormConfigInterface { return $this->inner->getConfig(); }
    public function isSubmitted(): bool { return $this->inner->isSubmitted(); }
    public function getName(): string { return $this->inner->getName(); }
    public function getPropertyPath(): ?PropertyPathInterface { return $this->inner->getPropertyPath(); }
    public function addError(FormError $error): static { $this->inner->addError($error); return $this; }
    public function isRequired(): bool { return $this->inner->isRequired(); }
    public function isDisabled(): bool { return $this->inner->isDisabled(); }
    public function isEmpty(): bool { return $this->inner->isEmpty(); }
    public function isSynchronized(): bool { return $this->inner->isSynchronized(); }
    public function getTransformationFailure(): ?TransformationFailedException { return $this->inner->getTransformationFailure(); }
    public function initialize(): static { $this->inner->initialize(); return $this; }
    public function handleRequest(mixed $request = null): static { $this->inner->handleRequest($request); return $this; }
    public function getRoot(): FormInterface { return $this->inner->getRoot(); }
    public function isRoot(): bool { return $this->inner->isRoot(); }
    public function createView(?FormView $parent = null): FormView { return $this->inner->createView($parent); }

    // ArrayAccess
    public function offsetExists(mixed $offset): bool { return $this->inner->offsetExists($offset); }
    public function offsetGet(mixed $offset): mixed { return $this->inner->offsetGet($offset); }
    public function offsetSet(mixed $offset, mixed $value): void { $this->inner->offsetSet($offset, $value); }
    public function offsetUnset(mixed $offset): void { $this->inner->offsetUnset($offset); }

    // Traversable
    public function getIterator(): \Traversable { return $this->inner; }

    // Countable
    public function count(): int { return $this->inner->count(); }
}