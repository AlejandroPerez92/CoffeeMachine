<?php
declare(strict_types=1);

namespace AlexPerez\CoffeeMachine\Shared\Domain\Aggregate;

use AlexPerez\CoffeeMachine\Shared\Domain\EventBus\DomainEvent;

abstract class AggregateRoot
{
    private array $domainEvents = [];

    final public function pullDomainEvents(): array
    {
        $domainEvents = $this->domainEvents;
        $this->domainEvents = [];

        return $domainEvents;
    }

    final protected function pushEvent(DomainEvent $domainEvent): void
    {
        $this->domainEvents[] = $domainEvent;
    }

    abstract public static function aggregateId(): string;
}