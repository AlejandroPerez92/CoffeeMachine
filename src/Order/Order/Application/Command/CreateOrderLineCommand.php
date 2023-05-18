<?php
declare(strict_types=1);

namespace AlexPerez\CoffeeMachine\Order\Order\Application\Command;

final class CreateOrderLineCommand
{
    private string $productName;
    private int $units;
    private string $orderId;

    public function __construct(string $productName, int $units, string $orderId)
    {
        $this->productName = $productName;
        $this->units = $units;
        $this->orderId = $orderId;
    }

    public function productName(): string
    {
        return $this->productName;
    }

    public function units(): int
    {
        return $this->units;
    }

    public function orderId(): string
    {
        return $this->orderId;
    }

}