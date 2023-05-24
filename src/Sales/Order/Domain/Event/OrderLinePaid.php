<?php

declare(strict_types=1);

namespace AlexPerez\CoffeeMachine\Sales\Order\Domain\Event;

use AlexPerez\CoffeeMachine\Sales\Order\Domain\Order;
use AlexPerez\CoffeeMachine\Shared\Domain\EventBus\DomainEvent;

final class OrderLinePaid extends DomainEvent
{
    public function __construct(
        public readonly string $orderId,
        public readonly string $productName,
        public readonly int $total,
    ) {
        parent::__construct(Order::aggregateId());
        $this->payload = [
            'orderId' => $orderId,
            'productName' => $productName,
            'total' => $total,
        ];
    }

    public static function name(): string
    {
        return 'order_line_paid';
    }
}