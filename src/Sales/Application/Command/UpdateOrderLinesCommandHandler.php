<?php
declare(strict_types=1);

namespace Deliverea\CoffeeMachine\Sales\Application\Command;

use Deliverea\CoffeeMachine\Sales\Domain\Exception\NotFoundOrderException;
use Deliverea\CoffeeMachine\Sales\Domain\Order;
use Deliverea\CoffeeMachine\Sales\Domain\OrderLine;
use Deliverea\CoffeeMachine\Sales\Domain\OrderRepositoryInterface;

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
            $order = $this->orderRepository->getOrderOrFail($command->orderId());
        }catch (NotFoundOrderException){
            $order = new Order($command->orderId(),[],false);
        }
        $order->addLine(new OrderLine($command->productName(),$command->total()));
        $this->orderRepository->save($order);
    }

}