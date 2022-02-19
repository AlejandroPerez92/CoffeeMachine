<?php

namespace Deliverea\CoffeeMachine\Order\Domain;

interface OrderRepositoryInterface
{
    public function getByIdOrFail(OrderId $id): Order;

    public function save(Order $order): void;
}