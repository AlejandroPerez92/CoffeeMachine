<?php

declare(strict_types=1);

namespace AlexPerez\CoffeeMachine\Sales\ProductSales\Application\Command;

final class AccountProductCommand
{
    public function __construct(
        public readonly string $product,
        public readonly int $total,
    ) {
    }
}