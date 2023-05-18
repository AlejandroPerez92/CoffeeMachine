<?php
declare(strict_types=1);

namespace AlexPerez\CoffeeMachine\Shared\Infrastructure\Eventbus;

use AlexPerez\CoffeeMachine\Shared\Domain\EventBus\DomainEvent;
use AlexPerez\CoffeeMachine\Shared\Domain\EventBus\EventBusInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;

final class EventBusWrapper extends EventDispatcher implements EventBusInterface
{
    public function publish(DomainEvent ...$events): void
    {
        foreach ($events as $event) {
            $this->dispatch($event, $event->aggregateId().'.'.$event->name());
        }
    }
}