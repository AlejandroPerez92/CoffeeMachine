<?php

namespace AlexPerez\CoffeeMachine\Tests\Integration\Sales\Order\Infrastructure;

use AlexPerez\CoffeeMachine\Sales\Order\Domain\NotFoundOrderException;
use AlexPerez\CoffeeMachine\Sales\Order\Domain\Order;
use AlexPerez\CoffeeMachine\Sales\Order\Domain\OrderLine;
use AlexPerez\CoffeeMachine\Sales\Order\Domain\OrderStatus;
use AlexPerez\CoffeeMachine\Sales\Order\Infrastructure\MongoDBOrderRepository;
use AlexPerez\CoffeeMachine\Shared\Domain\Order\OrderId;
use AlexPerez\CoffeeMachine\Tests\Integration\IntegrationTestCase;
use Doctrine\ODM\MongoDB\DocumentManager;

class MongoDBOrderRepositoryTest extends IntegrationTestCase
{
    private MongoDBOrderRepository $repository;
    private DocumentManager $documentManager;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = $this->getContainer()->get('alex-perez.sales.order.repository');
        $this->documentManager = $this->getContainer()->get(DocumentManager::class);
    }

    public function testSaveAndRetrieveOrder()
    {
        $order = $this->createOrder();
        $this->repository->save($order);

        $this->documentManager->clear();
        $retrievedOrder = $this->repository->getOrderOrFail($order->id());
        $this->assertEquals($order->id()->value(), $retrievedOrder->id()->value());
        $this->assertCount(0, $retrievedOrder->lines());
        $this->assertEquals(OrderStatus::pending, $retrievedOrder->status());
    }

    public function testGetOrderOrFailThrowsExceptionWhenNotFound()
    {
        $this->expectException(NotFoundOrderException::class);
        $this->repository->getOrderOrFail(OrderId::Create());
    }

    public function testUpdateOrder()
    {
        // Create and save an initial order
        $order = Order::create(OrderId::create(), []);
        $this->repository->save($order);

        // Retrieve the order
        $retrievedOrder = $this->repository->getOrderOrFail($order->id());

        // Add a line to the order
        $orderLine = new OrderLine('Coffee', 500);
        $retrievedOrder->addLine($orderLine);
        $retrievedOrder->pay();

        $this->repository->save($retrievedOrder);

        $this->documentManager->clear();
        $updatedOrder = $this->repository->getOrderOrFail($order->id());
        $this->assertEquals($order->id()->value(), $updatedOrder->id()->value());
        $this->assertCount(1, $updatedOrder->lines());
        $this->assertEquals(OrderStatus::paid, $updatedOrder->status());
        $this->assertEquals('Coffee', $updatedOrder->lines()[0]->productName);
        $this->assertEquals(500, $updatedOrder->lines()[0]->total);
    }

    private function createOrder(): Order
    {
        return Order::create(
            OrderId::Create(),
            []
        );
    }
}