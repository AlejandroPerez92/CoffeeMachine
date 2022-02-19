<?php
declare(strict_types=1);

namespace Deliverea\CoffeeMachine\Order\Application\Query;

use Deliverea\CoffeeMachine\Order\Domain\OrderId;
use Deliverea\CoffeeMachine\Order\Domain\OrderRepositoryInterface;

final class GetOrderQueryHandler
{
    private OrderRepositoryInterface $orderRepository;

    public function __construct(OrderRepositoryInterface $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function handle(GetOrderQueryObjectInterface $object): GetOrderQueryObjectInterface
    {
        $order = $this->orderRepository->getByIdOrFail(new OrderId($object->orderId()));
        $object->fill($order);
        return $object;
    }
}