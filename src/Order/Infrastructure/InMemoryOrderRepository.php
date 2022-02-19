<?php
declare(strict_types=1);

namespace Deliverea\CoffeeMachine\Order\Infrastructure;

use Deliverea\CoffeeMachine\Order\Domain\Exception\NotFoundOrderException;
use Deliverea\CoffeeMachine\Order\Domain\Order;
use Deliverea\CoffeeMachine\Order\Domain\OrderId;
use Deliverea\CoffeeMachine\Order\Domain\OrderRepositoryInterface;

final class InMemoryOrderRepository implements OrderRepositoryInterface
{
    private array $data;

    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    public function getByIdOrFail(OrderId $id): Order
    {
        if (!isset($this->data[$id->value()])) {
            throw new NotFoundOrderException($id);
        };

        return $this->data[$id->value()];
    }

    public function save(Order $order): void
    {
        $this->data[$order->id()->value()] = $order;
    }
}