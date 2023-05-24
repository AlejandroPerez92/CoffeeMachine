<?php
declare(strict_types=1);

namespace AlexPerez\CoffeeMachine\Sales\Order\Application\UpdateOrderLines;

final class UpdateOrderLinesCommand
{
    public function __construct(
        public readonly string $orderId,
        public readonly string $productName,
        public readonly int $total,
    ) {
    }
}