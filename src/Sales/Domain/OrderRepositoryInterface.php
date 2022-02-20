<?php

namespace Deliverea\CoffeeMachine\Sales\Domain;

use Deliverea\CoffeeMachine\Shared\Domain\Order\OrderId;

interface OrderRepositoryInterface
{
    public function getOrderOrFail(OrderId $orderId): Order;
    public function save(Order $order);
}