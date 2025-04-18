<?php
declare(strict_types=1);

namespace AlexPerez\CoffeeMachine\Order\Order\Infrastructure\Controller\Request;

class AddOrderLineRequest
{
    public function __construct(
        private readonly string $product_name = '',
        private readonly int $units = 1
    ) {
    }

    public function productName(): string
    {
        return strtolower($this->product_name);
    }

    public function units(): int
    {
        return $this->units;
    }
}