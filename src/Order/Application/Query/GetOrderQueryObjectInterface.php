<?php

namespace Deliverea\CoffeeMachine\Order\Application\Query;

use Deliverea\CoffeeMachine\Order\Domain\Order;

interface GetOrderQueryObjectInterface
{
    public function orderId(): string;
    public function fill(Order $order);
}