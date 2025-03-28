<?php
declare(strict_types=1);

namespace AlexPerez\CoffeeMachine\Sales\Order\Infrastructure;

use AlexPerez\CoffeeMachine\Sales\Order\Domain\NotFoundOrderException;
use AlexPerez\CoffeeMachine\Sales\Order\Domain\Order;
use AlexPerez\CoffeeMachine\Sales\Order\Domain\OrderRepositoryInterface;
use AlexPerez\CoffeeMachine\Shared\Domain\Order\OrderId;

final class InMemoryOrderRepository implements OrderRepositoryInterface
{
    public function __construct(private array $data = [])
    {
    }

    public function getOrderOrFail(OrderId $orderId): Order
    {
        if (!isset($this->data[$orderId->value()])) {
            throw new NotFoundOrderException($orderId);
        }

        return $this->data[$orderId->value()];
    }

    public function save(Order $order): void
    {
        $this->data[$order->id()->value()] = $order;
    }
}

