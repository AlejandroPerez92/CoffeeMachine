<?php

namespace AlexPerez\CoffeeMachine\Shared\Domain\EventBus;

interface EventSubscriberInterface
{
    public function getSubscribedEvents(): array;
}