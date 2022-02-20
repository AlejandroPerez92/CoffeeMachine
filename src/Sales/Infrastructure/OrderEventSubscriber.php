<?php
declare(strict_types=1);

namespace Deliverea\CoffeeMachine\Sales\Infrastructure;

use Deliverea\CoffeeMachine\Sales\Application\Command\UpdateOrderLinesCommand;
use Deliverea\CoffeeMachine\Sales\Application\Command\UpdateSaleCommand;
use Deliverea\CoffeeMachine\Shared\Domain\EventBus\DomainEvent;
use League\Tactician\CommandBus;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class OrderEventSubscriber implements EventSubscriberInterface
{
    private CommandBus $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'order.line.added' => 'onLineAdded',
            'order.paid'       => 'onOrderPaid'
        ];
    }

    public function onLineAdded(DomainEvent $event)
    {
        $this->commandBus->handle(new UpdateOrderLinesCommand($event->payload()['orderId'],
            $event->payload()['product'],
            $event->payload()['total']));
    }

    public function onOrderPaid(DomainEvent $event)
    {
        $this->commandBus->handle(new UpdateSaleCommand($event->payload()['orderId'], $event->payload()['total']));
    }
}