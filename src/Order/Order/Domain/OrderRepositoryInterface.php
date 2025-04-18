<?php

namespace AlexPerez\CoffeeMachine\Order\Order\Domain;
use AlexPerez\CoffeeMachine\Shared\Domain\Order\OrderId;

interface OrderRepositoryInterface
{
    public function getByIdOrFail(OrderId $id): Order;

    public function save(Order $order): void;

    /**
     * @return Order[]
     */
    public function findAll(): array;
}
