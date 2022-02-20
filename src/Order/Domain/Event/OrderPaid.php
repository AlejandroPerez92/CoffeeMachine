<?php
declare(strict_types=1);

namespace Deliverea\CoffeeMachine\Order\Domain\Event;

use Deliverea\CoffeeMachine\Order\Domain\Order;
use Deliverea\CoffeeMachine\Shared\Domain\EventBus\DomainEvent;

final class OrderPaid extends DomainEvent
{
    public const NAME = 'paid';

    public function __construct(Order $order)
    {
        parent::__construct(Order::aggregateId());
        $this->payload = [
            'orderId' => $order->id()->value(),
            'total'   => $order->total()->value()
        ];
    }

    public static function name(): string
    {
        return OrderPaid::NAME;
    }
}