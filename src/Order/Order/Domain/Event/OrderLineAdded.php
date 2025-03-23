<?php

declare(strict_types=1);

namespace AlexPerez\CoffeeMachine\Order\Order\Domain\Event;

use AlexPerez\CoffeeMachine\Order\Order\Domain\Order;
use AlexPerez\CoffeeMachine\Shared\Domain\Order\OrderId;
use AlexPerez\CoffeeMachine\Order\Order\Domain\OrderLine;
use AlexPerez\CoffeeMachine\Shared\Domain\EventBus\DomainEvent;

final readonly class OrderLineAdded extends DomainEvent
{
    const NAME = 'line_added';

    public function __construct(
        public string $orderId,
        public string $product,
        public int $units,
        public int $total,
    ) {
        parent::__construct(
            Order::aggregateId(),
        );
    }

    public static function fromOrder(
        OrderId $orderId,
        OrderLine $orderLine,
    ): self {
        return new self(
            $orderId->value(),
            $orderLine->productName,
            $orderLine->units->value(),
            $orderLine->total->value(),
        );
    }

    public static function name(): string
    {
        return OrderLineAdded::NAME;
    }
}