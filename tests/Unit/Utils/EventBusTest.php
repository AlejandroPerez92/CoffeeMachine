<?php
declare(strict_types=1);

namespace AlexPerez\CoffeeMachine\Tests\Unit\Utils;

use AlexPerez\CoffeeMachine\Shared\Domain\EventBus\DomainEvent;
use AlexPerez\CoffeeMachine\Shared\Domain\EventBus\EventBusInterface;

final class EventBusTest implements EventBusInterface
{
    private array $events;

    public function publish(DomainEvent ...$events): void
    {
        foreach ($events as $event) {
            $this->events[$event->aggregateId() . '.' . $event->name()][] = $event;
        }
    }

    public function hasEvents(string $name): bool
    {
        return isset($this->events[$name]) && count($this->events[$name]) > 0;
    }
}