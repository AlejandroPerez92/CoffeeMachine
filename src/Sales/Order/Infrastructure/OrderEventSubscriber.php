<?php
declare(strict_types=1);

namespace AlexPerez\CoffeeMachine\Sales\Order\Infrastructure;

use AlexPerez\CoffeeMachine\Sales\Order\Application\UpdateOrderLines\UpdateOrderLinesCommand;
use AlexPerez\CoffeeMachine\Sales\Order\Application\UpdateOrderStatus\UpdateOrderStatusCommand;
use AlexPerez\CoffeeMachine\Shared\Domain\EventBus\DomainEvent;
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

    public function onLineAdded(DomainEvent $event): void
    {
        $this->commandBus->handle(
            new UpdateOrderLinesCommand($event->payload()['orderId'],
            $event->payload()['product'],
            $event->payload()['total'])
        );
    }

    public function onOrderPaid(DomainEvent $event): void
    {
        $this->commandBus->handle(new UpdateOrderStatusCommand($event->payload()['orderId'],'paid'));
    }
}