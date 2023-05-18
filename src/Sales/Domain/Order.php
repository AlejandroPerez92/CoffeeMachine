<?php
declare(strict_types=1);

namespace AlexPerez\CoffeeMachine\Sales\Domain;

use AlexPerez\CoffeeMachine\Shared\Domain\Order\OrderId;

final class Order
{
    private OrderId $id;
    private array $lines;
    private bool $paid;

    public function __construct(OrderId $id, array $lines, bool $paid)
    {
        $this->id = $id;
        $this->lines = $lines;
        $this->paid = $paid;
    }

    public function addLine(OrderLine $line): void
    {
        $this->lines[] = $line;
    }

    public function id(): OrderId
    {
        return $this->id;
    }

    public function lines(): array
    {
        return $this->lines;
    }

    public function paid(): bool
    {
        return $this->paid;
    }

    public function pay(): void
    {
        $this->paid = true;
    }

}