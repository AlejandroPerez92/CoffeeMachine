<?php
declare(strict_types=1);

namespace AlexPerez\CoffeeMachine\Sales\ProductSales\Domain;

final class OrderLine
{
    private string $productName;
    private int $total;

    public function __construct(string $productName, int $total)
    {
        $this->productName = $productName;
        $this->total = $total;
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