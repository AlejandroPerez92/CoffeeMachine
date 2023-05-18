<?php
declare(strict_types=1);

namespace AlexPerez\CoffeeMachine\Order\Domain\Event;

use AlexPerez\CoffeeMachine\Order\Domain\Order;
use AlexPerez\CoffeeMachine\Shared\Domain\EventBus\DomainEvent;

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