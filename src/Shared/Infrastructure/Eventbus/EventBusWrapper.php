<?php
declare(strict_types=1);

namespace Deliverea\CoffeeMachine\Shared\Infrastructure\Eventbus;

use Deliverea\CoffeeMachine\Shared\Domain\EventBus\DomainEvent;
use Deliverea\CoffeeMachine\Shared\Domain\EventBus\EventBusInterface;
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