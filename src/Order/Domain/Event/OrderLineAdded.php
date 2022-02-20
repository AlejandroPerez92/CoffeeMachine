<?php
declare(strict_types=1);

namespace Deliverea\CoffeeMachine\Order\Domain\Event;

use Deliverea\CoffeeMachine\Order\Domain\Order;
use Deliverea\CoffeeMachine\Shared\Domain\Order\OrderId;
use Deliverea\CoffeeMachine\Order\Domain\OrderLine;
use Deliverea\CoffeeMachine\Shared\Domain\EventBus\DomainEvent;

final class OrderLineAdded extends DomainEvent
{
    const NAME = 'line.added';

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