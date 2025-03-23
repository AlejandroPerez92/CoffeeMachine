<?php
declare(strict_types=1);

namespace AlexPerez\CoffeeMachine\Order\Order\Application\Query;

use AlexPerez\CoffeeMachine\Shared\Domain\Order\OrderId;
use AlexPerez\CoffeeMachine\Order\Order\Domain\OrderRepositoryInterface;

final class GetOrderQueryHandler
{
    public function __construct(private OrderRepositoryInterface $orderRepository)
    {
    }

    public function handle(GetOrderQueryObjectInterface $object): GetOrderQueryObjectInterface
    {
        $order = $this->orderRepository->getByIdOrFail(new OrderId($object->orderId()));
        $object->fill($order);
        return $object;
    }
}