<?php
declare(strict_types=1);

namespace AlexPerez\CoffeeMachine\Sales\Order\Application\UpdateOrderLines;

final readonly class UpdateOrderLinesCommand
{
    public function __construct(
        public string $orderId,
        public string $productName,
        public int $total,
    ) {
    }
}