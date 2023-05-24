<?php
declare(strict_types=1);

namespace AlexPerez\CoffeeMachine\Sales\Order\Application\UpdateOrderStatus;

final class UpdateOrderStatusCommand
{
    public function __construct(public readonly string $orderId, public readonly string $status)
    {
    }
}