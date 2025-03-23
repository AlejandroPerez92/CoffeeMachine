<?php
declare(strict_types=1);

namespace AlexPerez\CoffeeMachine\Order\Order\Application\Command;

final readonly class CreateOrderLineCommand
{
    public function __construct(
        public string $productName,
        public int $units,
        public string $orderId,
    )
    {
    }

}