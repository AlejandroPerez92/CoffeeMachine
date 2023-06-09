<?php
declare(strict_types=1);

namespace AlexPerez\CoffeeMachine\Order\Order\Application\Command;

use AlexPerez\CoffeeMachine\Shared\Domain\Order\OrderId;

final class PayOrderCommand
{
    private OrderId $orderId;
    private int $amount;

    public function __construct(OrderId $orderId, int $amount)
    {
        $this->orderId = $orderId;
        $this->amount = $amount;
    }

    public function orderId(): OrderId
    {
        return $this->orderId;
    }

    public function amount(): int
    {
        return $this->amount;
    }

}