<?php
declare(strict_types=1);

namespace AlexPerez\CoffeeMachine\Sales\Order\Domain;

final readonly class OrderLine
{
    public function __construct(
        public string $productName,
        public int $total,
    ) {
    }
}