<?php
declare(strict_types=1);

namespace Deliverea\CoffeeMachine\Shared\Domain\EventBus;

use Deliverea\CoffeeMachine\Shared\Domain\Uuid\Uuid;

abstract class DomainEvent
{
    private string $eventId;
    protected \DateTimeImmutable $occurredOn;
    private string $aggregateId;
    protected array $payload;

    public function __construct($aggregateId)
    {
        $this->eventId = Uuid::Create()->value();
        $this->occurredOn = new \DateTimeImmutable();
        $this->aggregateId = $aggregateId;
    }

    public function eventId(): string
    {
        return $this->eventId;
    }

    public function occurredOn(): \DateTimeImmutable
    {
        return $this->occurredOn;
    }

    public function aggregateId(): string
    {
        return $this->aggregateId;
    }

    public function payload(): array
    {
        return $this->payload;
    }

    abstract public static function name(): string;

}