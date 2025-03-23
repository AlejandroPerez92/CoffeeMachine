<?php

declare(strict_types=1);

namespace AlexPerez\CoffeeMachine\Sales\ProductSales\Application\Command;

final readonly class AccountProductCommand
{
    public function __construct(
        public string $product,
        public int $total,
    ) {
    }
}