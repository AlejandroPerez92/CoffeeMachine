<?php
declare(strict_types=1);

namespace AlexPerez\CoffeeMachine\Sales\ProductSales\Infrastructure;

use AlexPerez\CoffeeMachine\Sales\Order\Application\UpdateOrderLines\UpdateOrderLinesCommand;
use AlexPerez\CoffeeMachine\Sales\Order\Application\UpdateOrderStatus\UpdateOrderStatusCommand;
use AlexPerez\CoffeeMachine\Sales\ProductSales\Application\Command\AccountProductCommand;
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
            'sales.order.order_line_paid' => 'onLinePaid',
        ];
    }

    public function onLinePaid(DomainEvent $event): void
    {
        $this->commandBus->handle(
            new AccountProductCommand(
                $event->payload()['productName'],
                $event->payload()['total'],
            )
        );
    }
}