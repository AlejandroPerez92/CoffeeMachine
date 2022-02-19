<?php
declare(strict_types=1);

namespace Deliverea\CoffeeMachine\Order\Application\Command;

use Deliverea\CoffeeMachine\Order\Domain\OrderId;

final class CreateOrderCommand
{
    private OrderId $orderId;

    public function __construct(OrderId $orderId)
    {
        $this->orderId = $orderId;
    }

    public function orderId(): OrderId
    {
        return $this->orderId;
    }

}