<?php
declare(strict_types=1);

namespace AlexPerez\CoffeeMachine\Order\Order\Infrastructure;

use AlexPerez\CoffeeMachine\Order\Order\Domain\Exception\NotFoundOrderException;
use AlexPerez\CoffeeMachine\Order\Order\Domain\Order;
use AlexPerez\CoffeeMachine\Order\Order\Domain\OrderLine;
use AlexPerez\CoffeeMachine\Order\Order\Domain\OrderLineId;
use AlexPerez\CoffeeMachine\Shared\Domain\Money\Money;
use AlexPerez\CoffeeMachine\Shared\Domain\Order\OrderId;
use AlexPerez\CoffeeMachine\Order\Order\Domain\OrderRepositoryInterface;
use AlexPerez\CoffeeMachine\Shared\Domain\PositiveInteger\PositiveInteger;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\MongoDBException;
use MongoDB\Collection;

final class MongoDBOrderRepository implements OrderRepositoryInterface
{
    private Collection $collection;

    /**
     * @throws MongoDBException
     */
    public function __construct(private readonly DocumentManager $documentManager)
    {
        $this->collection = $this->documentManager->getDocumentCollection(Order::class);
    }

    public function getByIdOrFail(OrderId $id): Order
    {
        $document = $this->collection->findOne(['_id' => $id->value()]);

        if (null === $document) {
            throw new NotFoundOrderException($id);
        }

        return $this->hydrateOrder($document);
    }

    public function save(Order $order): void
    {
        $document = [
            '_id' => $order->id()->value(),
            'lines' => $this->serializeLines($order->lines()),
            'total' => ['value' => $order->total()->value()],
            'created' => $order->created(),
            'paid' => $order->paid(),
            'hot' => $order->hot()
        ];

        $this->collection->updateOne(
            ['_id' => $order->id()->value()],
            ['$set' => $document],
            ['upsert' => true]
        );
    }

    /**
     * @return Order[]
     */
    public function findAll(): array
    {
        $documents = $this->collection->find();
        $orders = [];

        foreach ($documents as $document) {
            $orders[] = $this->hydrateOrder($document);
        }

        return $orders;
    }

    private function hydrateOrder(array $document): Order
    {
        return Order::fromPrimitives(
            $document['_id'],
            $this->deserializeLines($document['lines'] ?? []),
            $document['total'] ?? ['value' => 0],
            $document['created'] instanceof \MongoDB\BSON\UTCDateTime 
                ? $document['created']->toDateTime() 
                : new \DateTimeImmutable(),
            $document['paid'] ?? false,
            $document['hot'] ?? false
        );
    }

    private function serializeLines(array $lines): array
    {
        $result = [];
        foreach ($lines as $productName => $line) {
            $result[$productName] = [
                'id' => $line->id->value(),
                'productName' => $line->productName,
                'total' => $line->total->value(),
                'units' => $line->units->value(),
                'created' => $line->created
            ];
        }
        return $result;
    }

    private function deserializeLines(array $lines): array
    {
        $result = [];
        foreach ($lines as $productName => $lineData) {
            $result[$productName] = new OrderLine(
                new OrderLineId($lineData['id']),
                $lineData['productName'],
                new Money($lineData['total']),
                new PositiveInteger($lineData['units']),
                $lineData['created'] instanceof \MongoDB\BSON\UTCDateTime 
                    ? $lineData['created']->toDateTime() 
                    : new \DateTimeImmutable()
            );
        }
        return $result;
    }
}
