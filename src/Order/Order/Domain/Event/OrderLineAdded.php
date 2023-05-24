<?php
declare(strict_types=1);

namespace AlexPerez\CoffeeMachine\Order\Order\Domain\Event;

use AlexPerez\CoffeeMachine\Order\Order\Domain\Order;
use AlexPerez\CoffeeMachine\Shared\Domain\Order\OrderId;
use AlexPerez\CoffeeMachine\Order\Order\Domain\OrderLine;
use AlexPerez\CoffeeMachine\Shared\Domain\EventBus\DomainEvent;

final class OrderLineAdded extends DomainEvent
{
    const NAME = 'line_added';

    public function __construct(OrderId $orderId, OrderLine $orderLine)
    {
        parent::__construct(Order::aggregateId());
        $this->payload = [
          'orderId' => $orderId->value(),
          'product' => $orderLine->productName(),
          'units' => $orderLine->units()->value(),
          'total' => $orderLine->total()->value()
        ];
    }

    public static function name(): string
    {
        return OrderLineAdded::NAME;
    }
}