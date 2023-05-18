<?php
declare(strict_types=1);

namespace AlexPerez\CoffeeMachine\Order\Application\Command;

use AlexPerez\CoffeeMachine\Shared\Domain\Order\OrderId;

final class CreateOrderCommand
{
    private OrderId $orderId;
    private bool $hot;

    public function __construct(OrderId $orderId, bool $hot)
    {
        $this->orderId = $orderId;
        $this->hot = $hot;
    }

    public function hot(): bool
    {
        return $this->hot;
    }

    public function orderId(): OrderId
    {
        return $this->orderId;
    }

}