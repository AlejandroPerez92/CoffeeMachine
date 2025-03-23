<?php
declare(strict_types=1);

namespace AlexPerez\CoffeeMachine\Sales\Order\Application\UpdateOrderLines;

use AlexPerez\CoffeeMachine\Sales\Order\Domain\NotFoundOrderException;
use AlexPerez\CoffeeMachine\Sales\Order\Domain\Order;
use AlexPerez\CoffeeMachine\Sales\Order\Domain\OrderLine;
use AlexPerez\CoffeeMachine\Sales\Order\Domain\OrderRepositoryInterface;
use AlexPerez\CoffeeMachine\Shared\Domain\Order\OrderId;

final readonly class UpdateOrderLinesCommandHandler
{
    public function __construct(private OrderRepositoryInterface $orderRepository)
    {
    }

    public function handle(UpdateOrderLinesCommand $command): void
    {
        try {
            $order = $this->orderRepository->getOrderOrFail(new OrderId($command->orderId));
        } catch (NotFoundOrderException) {
            $order = Order::create(new OrderId($command->orderId), []);
        }

        $order->addLine(new OrderLine($command->productName, $command->total));
        $this->orderRepository->save($order);
    }

}