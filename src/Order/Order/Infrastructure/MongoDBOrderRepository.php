<?php
declare(strict_types=1);

namespace AlexPerez\CoffeeMachine\Order\Order\Infrastructure;

use AlexPerez\CoffeeMachine\Order\Order\Domain\Exception\NotFoundOrderException;
use AlexPerez\CoffeeMachine\Order\Order\Domain\Order;
use AlexPerez\CoffeeMachine\Shared\Domain\Order\OrderId;
use AlexPerez\CoffeeMachine\Order\Order\Domain\OrderRepositoryInterface;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\MongoDBException;

final class MongoDBOrderRepository implements OrderRepositoryInterface
{
    /**
     * @throws MongoDBException
     */
    public function __construct(private readonly DocumentManager $documentManager)
    {
    }

    public function getByIdOrFail(OrderId $id): Order
    {
        $order = $this->documentManager->getRepository(Order::class)->find($id->value());

        if (null === $order) {
            throw new NotFoundOrderException($id);
        }

        return $order;
    }

    public function save(Order $order): void
    {
        $this->documentManager->persist($order);
        $this->documentManager->flush();
    }

    /**
     * @return Order[]
     */
    public function findAll(): array
    {
        return $this->documentManager->getRepository(Order::class)->findAll();
    }
}
