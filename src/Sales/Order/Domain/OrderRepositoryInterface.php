<?php

namespace AlexPerez\CoffeeMachine\Sales\Order\Domain;

use AlexPerez\CoffeeMachine\Shared\Domain\Order\OrderId;

interface OrderRepositoryInterface
{
    public function getOrderOrFail(OrderId $orderId): Order;
    public function save(Order $order): void;
}