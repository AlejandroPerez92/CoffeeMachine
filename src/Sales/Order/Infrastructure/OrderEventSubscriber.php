<?php

declare(strict_types=1);

namespace AlexPerez\CoffeeMachine\Sales\Order\Infrastructure;

use AlexPerez\CoffeeMachine\Order\Order\Domain\Event\OrderLineAdded;
use AlexPerez\CoffeeMachine\Order\Order\Domain\Event\OrderPaid;
use AlexPerez\CoffeeMachine\Sales\Order\Application\UpdateOrderLines\UpdateOrderLinesCommand;
use AlexPerez\CoffeeMachine\Sales\Order\Application\UpdateOrderStatus\UpdateOrderStatusCommand;
use League\Tactician\CommandBus;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class OrderEventSubscriber implements EventSubscriberInterface
{
    public function __construct(private readonly CommandBus $commandBus)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'order.order.line_added' => 'onLineAdded',
            'order.order.paid' => 'onOrderPaid'
        ];
    }

    public function onLineAdded(OrderLineAdded $event): void
    {
        $this->commandBus->handle(
            new UpdateOrderLinesCommand(
                $event->orderId,
                $event->product,
                $event->total,
            )
        );
    }

    public function onOrderPaid(OrderPaid $event): void
    {
        $this->commandBus->handle(
            new UpdateOrderStatusCommand(
                $event->orderId,
                'paid',
            ),
        );
    }
}