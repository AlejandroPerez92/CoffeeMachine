<?php
declare(strict_types=1);

namespace AlexPerez\CoffeeMachine\Order\Order\Infrastructure;

use AlexPerez\CoffeeMachine\Order\Order\Domain\Exception\NotFoundOrderException;
use AlexPerez\CoffeeMachine\Order\Order\Domain\Order;
use AlexPerez\CoffeeMachine\Shared\Domain\Order\OrderId;
use AlexPerez\CoffeeMachine\Order\Order\Domain\OrderRepositoryInterface;

final class InMemoryOrderRepository implements OrderRepositoryInterface
{
    public function __construct(private array $data = [])
    {
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

    public function findAll(): array
    {
        // TODO: Implement findAll() method.
    }
}