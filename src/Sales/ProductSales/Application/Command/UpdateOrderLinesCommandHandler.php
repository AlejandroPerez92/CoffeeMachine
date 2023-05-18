<?php
declare(strict_types=1);

namespace AlexPerez\CoffeeMachine\Sales\ProductSales\Application\Command;

use AlexPerez\CoffeeMachine\Sales\ProductSales\Domain\Exception\NotFoundOrderException;
use AlexPerez\CoffeeMachine\Sales\ProductSales\Domain\Order;
use AlexPerez\CoffeeMachine\Sales\ProductSales\Domain\OrderLine;
use AlexPerez\CoffeeMachine\Sales\ProductSales\Domain\OrderRepositoryInterface;
use AlexPerez\CoffeeMachine\Shared\Domain\Order\OrderId;

final class UpdateOrderLinesCommandHandler
{
    private OrderRepositoryInterface $orderRepository;

    public function __construct(OrderRepositoryInterface $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function handle(UpdateOrderLinesCommand $command): void
    {
        try{
            $order = $this->orderRepository->getOrderOrFail(new OrderId($command->orderId()));
        }catch (NotFoundOrderException){
            $order = new Order(new OrderId($command->orderId()),[],false);
        }
        $order->addLine(new OrderLine($command->productName(),$command->total()));
        $this->orderRepository->save($order);
    }

}