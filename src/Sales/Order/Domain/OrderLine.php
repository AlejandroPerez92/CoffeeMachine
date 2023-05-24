<?php
declare(strict_types=1);

namespace AlexPerez\CoffeeMachine\Sales\Order\Domain;

final class OrderLine
{
    public function __construct(
        public readonly string $productName,
        public readonly int $total,
    ) {
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