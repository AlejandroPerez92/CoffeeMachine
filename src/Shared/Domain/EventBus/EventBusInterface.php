<?php

namespace Deliverea\CoffeeMachine\Shared\Domain\EventBus;

interface EventBusInterface
{
    public function publish(DomainEvent $event): void;
}