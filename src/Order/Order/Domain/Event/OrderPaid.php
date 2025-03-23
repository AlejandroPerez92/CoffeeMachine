<?php
declare(strict_types=1);

namespace AlexPerez\CoffeeMachine\Order\Order\Domain\Event;

use AlexPerez\CoffeeMachine\Order\Order\Domain\Order;
use AlexPerez\CoffeeMachine\Shared\Domain\EventBus\DomainEvent;

final readonly class OrderPaid extends DomainEvent
{
    public const NAME = 'paid';

    public function __construct(
        public string $orderId,
        public int $total,
    )
    {
        parent::__construct(
            Order::aggregateId(),
        );
    }

    public static function fromOrder(Order $order): self
    {
        return new self(
            $order->id()->value(),
            $order->total()->value(),
        );
    }

    public static function name(): string
    {
        return OrderPaid::NAME;
    }
}