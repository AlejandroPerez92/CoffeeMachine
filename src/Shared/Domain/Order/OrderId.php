<?php
declare(strict_types=1);

namespace AlexPerez\CoffeeMachine\Shared\Domain\Order;

use AlexPerez\CoffeeMachine\Shared\Domain\Uuid\Uuid;

final class OrderId
{
    private Uuid $id;

    public function __construct(string $id)
    {
        $this->id = new Uuid($id);
    }

    public static function create(): self
    {
        return new self((string) Uuid::Create());
    }

    public static function fromString(string $value): self
    {
        return new self($value);
    }

    public function value(): string
    {
        return (string) $this->id;
    }
}