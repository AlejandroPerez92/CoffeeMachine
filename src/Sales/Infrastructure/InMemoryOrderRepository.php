<?php
declare(strict_types=1);

namespace Deliverea\CoffeeMachine\Sales\Infrastructure;

use Deliverea\CoffeeMachine\Sales\Domain\Exception\NotFoundOrderException;
use Deliverea\CoffeeMachine\Sales\Domain\Order;
use Deliverea\CoffeeMachine\Sales\Domain\OrderRepositoryInterface;
use Deliverea\CoffeeMachine\Shared\Domain\Order\OrderId;

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

