<?php
declare(strict_types=1);

namespace AlexPerez\CoffeeMachine\Sales\ProductSales\Application\Command;

final class UpdateOrderLinesCommand
{
    private string $orderId;
    private string $productName;
    private int $total;

    public function __construct(string $orderId, string $productName, int $total)
    {
        $this->orderId = $orderId;
        $this->productName = $productName;
        $this->total = $total;
    }

    public function orderId(): string
    {
        return $this->orderId;
    }

    public function productName(): string
    {
        return $this->productName;
    }

    public function total(): int
    {
        return $this->total;
    }
}