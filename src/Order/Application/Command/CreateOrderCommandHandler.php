<?php
declare(strict_types=1);

namespace Deliverea\CoffeeMachine\Order\Application\Command;

use Deliverea\CoffeeMachine\Order\Domain\Order;
use Deliverea\CoffeeMachine\Order\Domain\OrderRepositoryInterface;

final class CreateOrderCommandHandler
{
    private OrderRepositoryInterface $orderRepository;

    public function __construct(
        OrderRepositoryInterface $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function handle(CreateOrderCommand $command): void
    {
        $order = Order::Create($command->orderId(), $command->hot());
        $this->orderRepository->save($order);
    }
}