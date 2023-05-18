<?php

namespace AlexPerez\CoffeeMachine\Order\Application\Query;

use AlexPerez\CoffeeMachine\Order\Domain\Order;

interface GetOrderQueryObjectInterface
{
    public function orderId(): string;
    public function fill(Order $order);
}