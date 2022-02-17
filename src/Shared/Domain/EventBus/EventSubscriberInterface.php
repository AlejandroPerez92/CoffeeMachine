<?php

namespace Deliverea\CoffeeMachine\Shared\Domain\EventBus;

interface EventSubscriberInterface
{
    public function getSubscribedEvents(): array;
}