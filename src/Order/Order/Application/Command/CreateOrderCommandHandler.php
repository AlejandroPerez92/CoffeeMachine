<?php
declare(strict_types=1);

namespace AlexPerez\CoffeeMachine\Order\Order\Application\Command;

use AlexPerez\CoffeeMachine\Order\Order\Domain\Order;
use AlexPerez\CoffeeMachine\Order\Order\Domain\OrderRepositoryInterface;

final class CreateOrderCommandHandler
{
    public function __construct(private OrderRepositoryInterface $orderRepository)
    {
    }

    public function handle(CreateOrderCommand $command): void
    {
        $order = Order::create($command->orderId, $command->hot);
        $this->orderRepository->save($order);
    }
}