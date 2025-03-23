<?php
declare(strict_types=1);

namespace AlexPerez\CoffeeMachine\Sales\Order\Application\UpdateOrderStatus;

final readonly class UpdateOrderStatusCommand
{
    public function __construct(public string $orderId, public string $status)
    {
    }
}