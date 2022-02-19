<?php
declare(strict_types=1);

namespace Deliverea\CoffeeMachine\Order\Application\Command;

use Deliverea\CoffeeMachine\Order\Domain\Order;
use Deliverea\CoffeeMachine\Order\Domain\OrderLine;
use Deliverea\CoffeeMachine\Order\Domain\OrderRepositoryInterface;
use Deliverea\CoffeeMachine\Order\Domain\ProductRepositoryInterface;
use Deliverea\CoffeeMachine\Shared\Domain\PositiveInteger\PositiveInteger;

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
        $order = Order::Create($command->orderId());
        $this->orderRepository->save($order);
    }
}