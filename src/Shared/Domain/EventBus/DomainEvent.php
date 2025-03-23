<?php

declare(strict_types=1);

namespace AlexPerez\CoffeeMachine\Shared\Domain\EventBus;

abstract readonly class DomainEvent
{
    public function __construct(
        public string $aggregateId,
    ) {
    }

    abstract public static function name(): string;

}