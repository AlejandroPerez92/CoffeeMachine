<?php

namespace AlexPerez\CoffeeMachine\Order\Order\Application\Query;

use AlexPerez\CoffeeMachine\Order\Order\Domain\Order;

interface GetOrderQueryObjectInterface
{
    public function orderId(): string;
    public function fill(Order $order);
}