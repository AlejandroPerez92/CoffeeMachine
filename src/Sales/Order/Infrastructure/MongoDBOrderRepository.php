<?php
declare(strict_types=1);

namespace AlexPerez\CoffeeMachine\Sales\Order\Infrastructure;

use AlexPerez\CoffeeMachine\Sales\Order\Domain\NotFoundOrderException;
use AlexPerez\CoffeeMachine\Sales\Order\Domain\Order;
use AlexPerez\CoffeeMachine\Sales\Order\Domain\OrderRepositoryInterface;
use AlexPerez\CoffeeMachine\Shared\Domain\Order\OrderId;
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

    public function getOrderOrFail(OrderId $orderId): Order
    {
        $order = $this->documentManager->getRepository(Order::class)->find($orderId->value());

        if (null === $order) {
            throw new NotFoundOrderException($orderId);
        }

        return $order;
    }

    public function save(Order $order): void
    {
        $this->documentManager->persist($order);
        $this->documentManager->flush();
    }
}