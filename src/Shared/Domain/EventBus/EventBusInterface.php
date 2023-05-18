<?php

namespace AlexPerez\CoffeeMachine\Shared\Domain\EventBus;

interface EventBusInterface
{
    public function publish(DomainEvent ...$events): void;
}