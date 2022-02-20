<?php
declare(strict_types=1);

namespace Deliverea\CoffeeMachine\Sales\Infrastructure;

use Deliverea\CoffeeMachine\Sales\Domain\Exception\NotFoundOrderException;
use Deliverea\CoffeeMachine\Sales\Domain\Order;
use Deliverea\CoffeeMachine\Sales\Domain\OrderRepositoryInterface;

final class InMemoryOrderRepository implements OrderRepositoryInterface
{
    private array $data;

    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    public function getOrderOrFail(string $orderId): Order
    {
        if (!isset($this->data[$orderId])) {
            throw new NotFoundOrderException($orderId);
        };

        return $this->data[$orderId];
    }

    public function save(Order $order): void
    {
        $this->data[$order->id()] = $order;
    }
}

