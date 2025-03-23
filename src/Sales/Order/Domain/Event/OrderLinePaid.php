<?php

declare(strict_types=1);

namespace AlexPerez\CoffeeMachine\Sales\Order\Domain\Event;

use AlexPerez\CoffeeMachine\Sales\Order\Domain\Order;
use AlexPerez\CoffeeMachine\Shared\Domain\EventBus\DomainEvent;

final readonly class OrderLinePaid extends DomainEvent
{
    public function __construct(
        public string $orderId,
        public string $productName,
        public int $total,
    ) {
        parent::__construct(
            Order::aggregateId(),
        );
    }

    public static function name(): string
    {
        return 'order_line_paid';
    }
}