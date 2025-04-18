<?php
declare(strict_types=1);

namespace AlexPerez\CoffeeMachine\Sales\Order\Application\UpdateOrderStatus;

use AlexPerez\CoffeeMachine\Sales\Order\Domain\OrderRepositoryInterface;
use AlexPerez\CoffeeMachine\Sales\Order\Domain\OrderStatus;
use AlexPerez\CoffeeMachine\Shared\Domain\EventBus\EventBusInterface;
use AlexPerez\CoffeeMachine\Shared\Domain\Order\OrderId;

final readonly class UpdateOrderStatusCommandHandler
{
    public function __construct(
        private OrderRepositoryInterface $orderRepository,
        private EventBusInterface $eventBus,
    ) {
    }

    public function handle(UpdateOrderStatusCommand $command): void
    {
        $order = $this->orderRepository->getOrderOrFail(new OrderId($command->orderId));

        if (OrderStatus::paid === $order->status()) {
            return;
        }

        $order->pay();

        $this->orderRepository->save($order);
        $this->eventBus->publish(...$order->pullDomainEvents());
    }
}