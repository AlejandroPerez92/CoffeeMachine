<?php
declare(strict_types=1);

namespace AlexPerez\CoffeeMachine\Order\Order\Domain;

use AlexPerez\CoffeeMachine\Shared\Domain\Uuid\Uuid;

final class OrderLineId
{
    private Uuid $id;

    public function __construct(string $id)
    {
        $this->id = new Uuid($id);
    }

    public static function Create(): self
    {
        return new self((string) Uuid::Create());
    }

    public function value(): string
    {
        return (string) $this->id;
    }
}