<?php
declare(strict_types=1);

namespace AlexPerez\CoffeeMachine\Order\Order\Application\Command;

use AlexPerez\CoffeeMachine\Order\Order\Domain\OrderRepositoryInterface;
use AlexPerez\CoffeeMachine\Shared\Domain\EventBus\EventBusInterface;
use AlexPerez\CoffeeMachine\Shared\Domain\Money\Money;

final class PayOrderCommandHandler
{
    public function __construct(private OrderRepositoryInterface $orderRepository, private EventBusInterface $eventBus)
    {
    }

    public function handle(PayOrderCommand $command): void
    {
        $order = $this->orderRepository->getByIdOrFail($command->orderId);
        $order->pay(new Money($command->amount));
        $this->orderRepository->save($order);
        $this->eventBus->publish(...$order->pullDomainEvents());
    }
}