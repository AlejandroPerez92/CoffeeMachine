<?php

namespace Deliverea\CoffeeMachine\Sales\Domain;

interface OrderRepositoryInterface
{
    public function getOrderOrFail(string $orderId): Order;
    public function save(Order $order);
}