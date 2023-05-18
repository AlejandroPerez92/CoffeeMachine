<?php
declare(strict_types=1);

namespace AlexPerez\CoffeeMachine\Sales\Infrastructure;

use AlexPerez\CoffeeMachine\Sales\Domain\Exception\NotFoundOrderException;
use AlexPerez\CoffeeMachine\Sales\Domain\Order;
use AlexPerez\CoffeeMachine\Sales\Domain\OrderRepositoryInterface;
use AlexPerez\CoffeeMachine\Shared\Domain\Order\OrderId;

final class InMemoryOrderRepository implements OrderRepositoryInterface
{
    private array $data;

    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    public function getOrderOrFail(OrderId $orderId): Order
    {
        if (!isset($this->data[$orderId->value()])) {
            throw new NotFoundOrderException($orderId->value());
        };

        return $this->data[$orderId->value()];
    }

    public function save(Order $order): void
    {
        $this->data[$order->id()->value()] = $order;
    }
}

